<template>
<div>
    <!-- Recent Blog Posts / End -->


    <!-- Section -->
    <div class="section gray">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8">

                    <!-- Section Headline -->
                    <div class="section-headline margin-top-60 margin-bottom-35">
                        <h4>Recent Posts</h4>
                    </div>

                    <!-- Blog Post -->
                    <div v-for="post in posts.data" :key="post.id">
                        <a :href="slug(post.slug)" class="blog-post">
                            <!-- Blog Post Thumbnail -->
                            <div class="blog-post-thumbnail">
                                <div v-for="cat in post.categories" :key="cat.id" class="blog-post-thumbnail-inner">
                                    <span class="blog-item-tag shadow-lg">
                                        {{ cat.title }}
                                    </span>
                                    <img :src="image(post.media)" alt="">
                                </div> 
                            </div>
                            <!-- Blog Post Content -->
                            <div class="blog-post-content">
                                <span class="blog-post-date">{{ post.created_at }}</span>
                                <h3>{{ post.title }}</h3>
                                <div class="" v-html="post.body.substr(0, 200) "></div>
                            </div>
                            <!-- Icon -->
                            <div class="entry-icon"></div>
                        </a>
                        
                    </div>
                    <div class="clearfix"></div>
                    <pagination :data="posts" @pagination-change-page="getResults"></pagination>

                    <!-- Pagination -->

                    <!-- Pagination / End -->

                </div>


                <div class="col-xl-4 col-lg-4 content-left-offset">
                    <div class="sidebar-container margin-top-65">
                        <!-- Widget -->
                        <div class="sidebar-widget">

                            <h3>Trending Posts</h3>
                            <ul class="widget-tabs">

                                <!-- Post #1 -->
                                <div v-for="post in trending" :key="post.id">
                                    <li>
                                        <a :href="slug(post.slug)" class="widget-content active">
                                            <img src="assets/images/blog-02a.jpg" alt="">
                                            <div class="widget-text">
                                                <h5>{{ post.title }}</h5>
                                                <span>{{ post.created_at }}</span>
                                            </div>
                                        </a>
                                    </li>	
                                </div>
                            </ul>

                        </div>
                        <!-- Widget / End-->

                        <!-- Widget -->
                        <div class="sidebar-widget">
                            <h3>Categories</h3>
                            <div class="task-tags text-capitalize">
                              <div class="inline m-2" v-for="category in categories" :key="category.id">
                                    <span class="cursor-pointer"  @click="toggleCategorySearch(category.slug)">
                                        {{ category.title }}
                                    </span>
                              </div>
                            </div>
                        </div>


                        <!-- Widget -->
                        <div class="sidebar-widget">
                            <h3>Tags</h3>
                            <div  class="task-tags text-lowercase">
                                <div class="inline m-2" v-for="tag in tags" :key="tag.id">
                                    <span class="cursor-pointer"  @click="toggleTagSearch(tag.slug)">
                                        {{ tag.title }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</template>
<script>

export default {

	data() {
		return {
			// Our data object that holds the Laravel paginator data
			posts: {},
            categories: {},
            tags: {},
            featured: {},
            trending: {},
            search: {
                category: '',
                tag: '',
            }
		}
	},
    

	methods: {
        toggleCategorySearch(slug){ 
            this.$set(this.search, 'category', slug);           
            this.search.tag = '';
            this.getResults();
        },

        toggleTagSearch(slug){
            this.$set(this.search, 'tag', slug);           
            this.search.category = '';
            this.getResults();
        },
        image(media){
            if(media.length > 0)
            {
                return media[0].id + '/' + media[0].file_name; 
            }

            return 'assets/images/blog-01a.jpg';
        },
        slug(slug){
            return 'blog/' + slug;
        },
		// Our method to GET results from a Laravel endpoint
		getResults(page = 1) {
            let params = this.search;
			axios.get('posts/?page=' + page, {
                params
            }).then(response => {
					this.posts = response.data;
            });
            
           
		}
    },
    
    mounted() {
		// Fetch initial results
        this.getResults();
        
         axios.get('posts/tags')
				.then(response => {
					this.tags = response.data;
        });
        
        axios.get('posts/categories/')
            .then(response => {
                this.categories = response.data;
        });
        
        axios.get('posts/trending')
            .then(response => {
                this.trending = response.data;
        });
        
        axios.get('posts/featured')
            .then(response => {
                this.featured = response.data;
        });
    },

}
</script>

<style scoped> 
    .pagination { 
        justify-content: center!important; 
    } 

   
</style>

