<template>
  <div class="container">
    <div class="row">
      <div class="col-xl-3 col-lg-4">
        <div class="sidebar-container w-full">
          <!-- Keywords -->
          <div class="sidebar-widget">
            <h3>Search</h3>
            <div class="keywords-container">
              <div class="keyword-input-container">
                <input type="text" placeholder="e.g. Freelancer name" v-model="search.title" />
                <button class="keyword-input-button ripple-effect" @click="getResults()">
                  <i class="icon-feather-search"></i>
                </button>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>

          <!-- Category -->
          <div class="sidebar-widget">
            <h3>Country</h3>
            <!-- class="selectpicker default" -->
            <select
              class="select-picker"
              data-size="10"
              v-model="search.country"
              @change="getResults()"
              data-live-search="false"
            >
              <option value>All Countries</option>
              <option
                v-for="country in countries"
                :key="country.id"
                :value="country.id"
              >{{ country.name }}</option>
            </select>
          </div>

          <!-- Budget -->
          <div class="sidebar-widget">
            <h3>Hourly Rate</h3>
            <div class="margin-top-10"></div>

            <div class="row">
              <div class="col-6">
                <div class="keywords-container">
                  <div class="keyword-input-container">
                    <input placeholder="Min" type="number" v-model="search.min_hourly_rate" />
                    <button class="keyword-input-button ripple-effect" @click="getResults()">
                      <i class="icon-feather-search"></i>
                    </button>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="col-6">
                <div class="keywords-container">
                  <div class="keyword-input-container">
                    <input placeholder="Max" type="number" v-model="search.max_hourly_rate" />
                    <button class="keyword-input-button ripple-effect" @click="getResults()">
                      <i class="icon-feather-search"></i>
                    </button>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tags -->
          <div class="sidebar-widget">
            <!-- class="skills-dropdown" -->
            <h3>Skills</h3>
            <select
              class="skills-dropdown w-full"
              multiple="multiple"
              v-model="search.skills"
            >
              <option v-for="skill in skills" :key="skill.id" :value="skill.title">{{ skill.title }}</option>
            </select>
            <div class="clearfix"></div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
      <div class="col-xl-9 col-lg-8 content-left-offset">
        <h3 class="page-title">Search Results</h3>

        <div class="notify-box margin-top-15">
          <div class="switch-container"></div>

          <div class="sort-by">
            <span>Sort by:</span>
            <select class="selectpicker hide-tick" v-model="search.sort" @change="getResults()">
              <option value="newest">Newest</option>
              <option value="oldest">Oldest</option>
              <option value="success">Success %</option>
              <option value="rating">Rating</option>
            </select>
          </div>
        </div>

        <div v-if="!isLoading">
          <!-- Freelancers List Container -->
          <div class="freelancers-container freelancers-list-layout compact-list margin-top-35">
            <!--Freelancer -->
            <div v-for="freelancer in freelancers.data" :key="freelancer.id" class="freelancer">
              <!-- Overview -->

              <div v-if="freelancer.id != profile.id" class="freelancer-overview">
                <div class="freelancer-overview-inner">
                  <!-- Bookmark Icon -->
                  <span class="bookmark-icon"></span>

                  <!-- Avatar -->
                  <div class="freelancer-avatar">
                    <!-- <div class="verified-badge"></div> -->
                    <a :href="link(freelancer)">
                      <img :src="image(freelancer)" alt />
                    </a>
                  </div>

                  <!-- Name -->
                  <div class="freelancer-name">
                    <h4>
                      <a :href="link(freelancer)">
                        {{ freelancer.name }}
                        <img
                          class="flag"
                          :src="country(freelancer.country)"
                          alt
                          :title="freelancer.country.name"
                          data-tippy-placement="top"
                        />
                      </a>
                    </h4>
                    <span>{{ truncate(freelancer.headline, 50) }}</span>
                    <!-- Rating -->
                    <div class="freelancer-rating">
                      <div class="star-rating" :data-rating="freelancer.rating"></div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Details -->
              <div v-if="freelancer.id != profile.id" class="freelancer-details">
                <div class="freelancer-details-list">
                  <ul>
                    <li>
                      Location
                      <strong>
                        <i class="icon-material-outline-location-on"></i>
                        {{ freelancer.country.name }}
                      </strong>
                    </li>
                    <li>
                      Rate
                      <strong>
                        ${{ freelancer.rate }} /
                        hr
                      </strong>
                    </li>
                    <li>
                      Job Success
                      <strong>
                        {{
                        truncate(freelancer.completion_rate, 5)
                        }}%
                      </strong>
                    </li>
                  </ul>
                </div>
                <a :href="link(freelancer)" class="button button-sliding-icon ripple-effect">
                  View Profile
                  <i class="icon-material-outline-arrow-right-alt"></i>
                </a>
              </div>
            </div>
            <!-- Freelancer / End -->
          </div>
          <!-- Freelancers Container / End -->

          <div v-if="!hasData" class="py-5">
            <p class="text-center py-5">No results found</p>
          </div>
        </div>

        <div v-if="isLoading" class="py-5">
          <loading></loading>
        </div>

        <!-- Pagination -->
        <div class="clearfix"></div>
        <pagination :data="freelancers" @pagination-change-page="getResults"></pagination>
        <!-- Pagination / End -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      timezone: null,
      profile: {},
      freelancers: {},
      countries: [],
      skills: {},
      search: {
        title: "",
        skills: [],
        country: "",
        sort: "",
        min_hourly_rate: "",
        max_hourly_rate: ""
      },
      hasData: false,
      isLoading: false
    };
  },

  mounted() {
    this.getResults();
    this.getCountries();
    this.getSkills();
    this.getUser();
  },

  methods: {
    link(freelancer) {
      return "freelancers/" + freelancer.uuid;
    },

    dateFormat(d){
			var date = Moment.tz(d, this.timezone).fromNow();
			// console.log(date);
			if(date == "Invalid date"){
				return d
			}
			return date;
			
		},

    truncate(text, no) {
      if(text == null || text == undefined){
        return ''
      }
      // console.log(text);
      
      text = String(text);
      text =  text.substring(0, no);
      //  console.log(text);
       return text;
    },

    skillChange(selected) {
      this.search.skills = selected;

      this.getResults();
    },

    image(freelancer) {
      if (
        freelancer.photo == null ||
        freelancer.photo == "" ||
        freelancer.photo == undefined
      ) {
        return "assets/images/user-avatar-placeholder.png";
      } else {
        return freelancer.photo;
      }
    },

    country(country) {
      return `assets/images/flags/${country.code.toLowerCase()}.svg`;
    },

    getUser(){
      axios
        .get("user")
        .then(response => {
          this.profile = response.data;
        });
    },

    getResults(page = 1) {
      this.isLoading = true;
      let params = this.search;
      axios
        .get("freelancers-api/?page=" + page, {
          params
        })
        .then(response => {
          this.isLoading = false;
          this.freelancers = response.data['freelancers'];
          this.timezone = response.data['timezone'];
          this.hasData = response.data['freelancers'].data.length == 0 ? false : true;
        });
    },

    getCountries() {
      axios.get("countries-api").then(response => {
        this.countries = response.data;
        this.$nextTick(function() {
          $(".select-picker").selectpicker();
          $(".select-picker").selectpicker("toggle");
          $(".select-picker").selectpicker("toggle");
        });
      });
    },

    getSkills() {
      var self = this;
      axios.get("skills-api").then(response => {
        this.skills = response.data;
        this.$nextTick(function() {
          $(".skills-dropdown")
            .select2({
              tags: true,
              placeholder: "Choose Skills",
              allowClear: true
            })
            .on("change", function(e) {
              var selected = $(this).val();
              self.skillChange(selected);
            });
        });
      });
    }
  }
};
</script>
