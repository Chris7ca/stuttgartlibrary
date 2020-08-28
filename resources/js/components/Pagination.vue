<template>

	<ul class="pagination uk-pagination" role="navigation" v-if="total > perPage">

		<li v-if="pages >= 2" uk-tooltip="First page">
			<a role="button" @click="getPage(1)" uk-icon="icon: chevron-double-left"></a>
		</li>

		<li v-for="(page, i) in visualPages" :key="i">
			<a
				role="button"
				:class="{ 'uk-text-bold uk-background-primary' : (currentPage  == page) }"
				@click="getPage(page)"
			>
				<span :class="{ 'uk-light uk-padding-small' : (currentPage  == page) }">{{ page }}</span>
			</a>
		</li>

		<li v-if="pages >= 2">
			<a
				role="button"
				@click="getPage(lastPage)"
				uk-icon="icon: chevron-double-right"
				uk-tooltip="Last page"
			></a>
		</li>

	</ul>

</template>

<script>
export default {
	props: ["sizeData", "perPage"],
	data() {
		return {
            total: 0,
			pages: 0,
			lastPage: 0,
			currentPage: 1,
		};
    },
    watch: {
        sizeData: function (size) {
            if ( !isNaN(this.sizeData) ) {
				this.currentPage = 1
                this.total = this.sizeData
                this.pages = Math.ceil(this.total / this.perPage) <= 1 ? 0 : Math.ceil(this.total / this.perPage)
                this.lastPage = this.pages
            }
        },
        perPage: function (size) {
            if ( !isNaN(this.sizeData) ) {
                this.total = this.sizeData
                this.pages = Math.ceil(this.total / this.perPage) <= 1 ? 0 : Math.ceil(this.total / this.perPage)
                this.lastPage = this.pages
            }
        }
    },
	computed: {
		visualPages: function() {

            let arrayPages = [];
            
			let ini = 0;
			let end = 0;
			let pages = [];

			if (parseInt(this.currentPage) <= 5) {
				ini = 1;
				if (parseInt(this.pages) < 10) end = parseInt(this.pages);
				else end = 10;
			} else if (parseInt(this.currentPage) >= parseInt(this.pages) - 4) {
				if (parseInt(this.pages) < 10) ini = 1;
				else ini = parseInt(this.pages) - 9;
				end = parseInt(this.pages);
			} else {
				ini = parseInt(this.currentPage) - 4;
				end = parseInt(this.currentPage) + 5;
			}

			for (let i = ini; i <= end; i++) {
				arrayPages.push(i);
			}

			return arrayPages;
		}
	},
	methods: {
		getPage: function(page) {
            this.currentPage = page
			this.$emit('updatePage', page)
		}
	},
	created () {
		this.total = this.sizeData
		this.pages = Math.ceil(this.total / this.perPage) <= 1 ? 0 : Math.ceil(this.total / this.perPage)
		this.lastPage = this.pages
	}
};
</script>