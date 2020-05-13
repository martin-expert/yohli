<template>
  <div class="container">
    <div class="row">
      <div class="col-xl-3 col-lg-4">
        <div class="sidebar-container">
          <!-- Keywords -->
          <div class="sidebar-widget">
            <h3>Search</h3>
            <div class="keywords-container">
              <div class="keyword-input-container">
                <input
                  type="text"
                  placeholder="e.g. job title"
                  v-model="search.title"
                />
                <button class="keyword-input-button ripple-effect" @click="getResults()">
                  <i class="icon-feather-search"></i>
                </button>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>

          <!-- Category -->
          <div class="sidebar-widget">
            <h3>Category</h3>
            <select
              data-size="7"
              class="select-picker z-200"
              v-model="search.industry"
              @change="getResults()"
              multiple
            >
              <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
              >{{ category.title }}</option>
            </select>
          </div>

          <div class="sidebar-widget">
            <h3>Budget Type</h3>
            <select
              class="selectpicker default"
              v-model="search.budget_type"
              @change="getResults()"
            >
              <option value>All</option>
              <option value="fixed">Fixed Price</option>
              <option value="hourly">Hourly Rate</option>
            </select>
          </div>

          <!-- Budget -->
          <div class="sidebar-widget">
            <h3>Budget Price</h3>
            <div class="margin-top-10"></div>
            
            <div class="row">
              <div class="col-6">
                <div class="keywords-container">
                  <div class="keyword-input-container">
                    <input placeholder="Min" type="number" v-model="search.min_budget">
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
                    <input placeholder="Max" type="number" v-model="search.max_budget">
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
            <h3>Skills</h3>

             <select
              class="skills-dropdown"
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
              <option value="lowest_price">Lowest Price</option>
              <option value="higest_price">Highest Price</option>
              <option value="most_bids">Most Bids</option>
              <option value="fewest_bids">Fewest Bids</option>
              <option value="yes">Featured</option>
            </select>
          </div>
        </div>

        <!-- Tasks Container -->
        <div class="tasks-list-container compact-list margin-top-35">
          <div v-if="!isLoading">
            <a v-for="job in jobs.data" :key="job.id" :href="slug(job.slug)" class="task-listing">
              <div class="task-listing-details">
                <div class="task-listing-description">
                  <h3 class="task-listing-title">{{ job.title }}</h3>
                  <ul class="task-icons">
                    <li>
                      <i class="icon-material-outline-location-on"></i>
                      {{ job.country == null ? " " :  job.country.name }}
                    </li>
                    <li>
                      <i class="icon-material-outline-access-time"></i>
                      {{ dateFormat(job.created_at) }}
                    </li>
                  </ul>
                  <p class="task-listing-text">{{ truncate(job.description) }}</p>
                  <div class="task-tags">
                    <span v-for="skill in job.skills" :key="skill.id" class="mr-1">{{ skill.title }}</span>
                    <span v-if="bidCheck(job)" class="bg-green-400 text-white px-4 py-2 shadow-sm">Bid Submitted</span>

                  </div>
                </div>
              </div>

              <div class="task-listing-bid">
                <div class="task-listing-bid-inner flex flex-row flex-wrap">
                  <div class="task-offers">
                    <strong>{{ budget(job) }}</strong>
                    <span>{{ budgetType(job) }}</span>
                  </div>
                  <span class="button button-sliding-icon ripple-effect min-w-xxxs" :class="[bidCheck(job) ? 'bg-gray-400' : '']">
                    {{ user.type == 'freelancer' ? bidCheck(job) ? "Pending Bid" : "Bid Now" : 'View' }}
                    <i class="icon-material-outline-arrow-right-alt"></i> 
                  </span>
                </div>
              </div>
            </a>

            <div v-if="!hasData" class="py-5">
              <p class="text-center py-5">No results found</p>
            </div>
          </div>

          <div v-if="isLoading" class="py-5">
            <loading></loading>
          </div>
        </div>
        <!-- Tasks Container / End -->

        <!-- Pagination -->
        <div class="clearfix my-2"></div>
        <pagination class="my-3" :data="jobs" @pagination-change-page="getResults"></pagination>
        <!-- Pagination / End -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  
  props : ['user'],
  data() {
    return {
      timezone: null,
      profile: {},
      jobs: {},
      categories: {},
      skills: {},
      search: {
        industry: [],
        title: "",
        skills: [],
        sort: "",
        budget_type: "",
        min_budget: "",
        max_budget: ""
      },
      slider: {
        value: [1, 3800],
        min: 1,
        max: 25000,
      },
      
      hasData: false,
      isLoading: false
    };
  },

  created() {
    this.slider.formatter = value => `$${value}`;
    this.slider.bgStyle = {
      backgroundColor: "#fff",
      boxShadow: "inset 0.5px 0.5px 3px 1px rgba(0,0,0,.36)"
    };
    this.slider.processStyle = {
      backgroundColor: "#ea7c11"
    };
    this.slider.tooltipStyle = {
      backgroundColor: "#000",
      borderColor: "#000"
    };
    
    let uri = window.location.search.substring(1); 
    let params = new URLSearchParams(uri);
    this.search.industry.push(params.get("category"));
    this.search.title = params.get("search");
  },

  mounted() {
    this.getResults();
    this.getCategories();
    this.getSkills();
    this.getUser();
  },

  methods: {
    slug(slug) {
      return "jobs/" + slug;
    },


    dateFormat(d){
			var date = Moment.tz(d, this.timezone).fromNow();
			// console.log(date);
			if(date == "Invalid date"){
				return d
			}
			return date;
			
		},

    bidCheck(job){
      let result = false;
      let self = this;
      for (let index = 0; index < job.bids.length; index++) {
        const element = job.bids[index];
        if(job.bids[index].profile_id == self.profile.id){
          result = true;
          return result
        } 
      }
      // console.log(result);
      
      return result;
    },

     getUser(){
      axios
        .get("user")
        .then(response => {
          this.profile = response.data;
        });
    },

    skillChange(selected) {
      this.search.skills = selected;

      this.getResults();
    },

    getResults(page = 1) {
      this.isLoading = true;
      let params = this.search;
      axios
        .get("jobs-api/?page=" + page, {
          params
        })
        .then(response => {
          this.isLoading = false;
          this.jobs = response.data['jobs'];
          this.timezone = response.data['timezone'];
          this.hasData = response.data['jobs'].data.length == 0 ? false : true;
        });
    },

    getCategories() {
      axios.get("job-categories-api").then(response => {
        this.categories = response.data;
         this.$nextTick(function(){ $('.select-picker').selectpicker(); });
      });
    },

    getSkills() {
      axios.get("skills-api").then(response => {
        this.skills = response.data;
      });
    },

    truncate(text) {
      return text.substring(0, 130) + "...";
    },

    budgetType(job) {
      if (job.budget_type == "fixed") {
        return "Fixed Price";
      } else {
        return "Hourly Rate";
      }
    },

    budget(job) {
      let currency = "$";
      if (job.min_budget == job.max_budget) {
        return currency + job.min_budget;
      } else {
        return `${currency}${job.min_budget} - ${currency}${job.max_budget}`;
      }
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
