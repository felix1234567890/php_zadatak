<template>
    <div>
        <div class="input-group flex-nowrap">
            <input
                type="text"
                class="form-control"
                placeholder="Search movies or tv shows"
                aria-label="Username"
                aria-describedby="addon-wrapping"
                v-model="searchTerm"
            />

            <select
                class="form-select input-group-text"
                aria-label="Default select example"
                v-model="type"
            >
                <option value="0" selected>Movies + TV-Shows</option>
                <option value="1">Movies</option>
                <option value="2">Tv-Shows</option>
            </select>

            <span class="input-group-text">
                <i class="fa fa-search"></i>
            </span>
        </div>
        <div v-if="searchData.length === 0">
            <p>No items to show</p>
        </div>
        <div class="row" v-else>
            <div
                class="col-md-4 col-sm-6 align-self-center"
                v-for="item in searchData"
                :key="item.id"
            >
                <div class="card" style="width: 20rem;">
                    <a :href="`/show/${item.id}`">
                        <img
                            :src="
                                item.poster_path
                                    ? `https://image.tmdb.org/t/p/w500/${item.poster_path}`
                                    : 'http://via.placeholder.com/500x750'
                            "
                            class="card-img-top"
                        />
                    </a>
                    <div class="card-body">
                        <a>
                            <h5 class="card-title">
                                {{ item.title }}
                            </h5>
                        </a>
                        <p class="card-text">
                            {{ shortenDescription(item.overview) }}
                        </p>
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
            searchTerm: "",
            type: "0",
            searchData: []
        };
    },
    watch: {
        searchTerm(value) {
            if (value.length < 3) return;
            axios
                .get("/api/search", {
                    params: { searchTerm: this.searchTerm }
                })
                .then(response => {
                    console.log(response.data);
                    this.searchData = response.data;
                })
                .catch(error => console.log(error));
        }
    },
    methods: {
        shortenDescription(text, len = 200) {
            if (text.length > len) return `${text.slice(0, len)}...`;
            return text;
        }
    }
};
</script>

<style></style>
