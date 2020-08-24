<template>
    <div>
        <div class="input-group flex-nowrap pt-5 pb-3">
            <input
                type="text"
                class="form-control"
                placeholder="Search movies or tv shows"
                aria-label="Username"
                aria-describedby="addon-wrapping"
                :value="searchTerm"
                @input="updateSearchTerm"
            />

            <span class="input-group-text">
                <select
                    class="form-select"
                    :value="type"
                    @input="updateSearchType"
                >
                    <option value="both" selected>Movies + TV-Shows</option>
                    <option value="movies">Movies</option>
                    <option value="shows">Tv-Shows</option>
                </select>
            </span>

            <span class="input-group-text" @click="searchForData">
                <i class="fa fa-search"></i>
            </span>
        </div>
        <div v-if="searchData.length === 0">
            <p>No items to show</p>
        </div>
        <div class="row" v-else>
            <div
                class="col-xs-12 col-sm-6 col-md-4 align-self-center"
                v-for="item in searchData"
                :key="item.id"
            >
                <div class="card m-2">
                    <router-link :to="`/show/${item.id}`">
                        <img :src="item.poster_path" class="card-img-top" />
                    </router-link>
                    <div class="card-body">
                        <router-link :to="`/show/${item.id}`">
                            <h5 class="card-title">
                                {{ item.title || item.name }}
                            </h5>
                        </router-link>
                        <p class="card-text">
                            {{ shortenDescription(item.description) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapMutations, mapState } from "vuex";
export default {
    computed: {
        ...mapState(["searchData", "searchTerm", "type"])
    },
    methods: {
        ...mapMutations(["setSearchData", "setSearchTerm", "setSearchType"]),
        shortenDescription(text, len = 200) {
            if (text.length > len) return `${text.slice(0, len)}...`;
            return text;
        },
        searchForData() {
            if (this.searchTerm.length < 3)
                alert("Please provide at least 3 letters");
            axios
                .get("/api/search", {
                    params: { searchTerm: this.searchTerm, type: this.type }
                })
                .then(response => {
                    this.setSearchData(response.data);
                })
                .catch(error => console.log(error));
        },
        updateSearchTerm(e) {
            this.setSearchTerm(e.target.value);
        },
        updateSearchType(e) {
            this.setSearchType(e.target.value);
        }
    }
};
</script>

<style>
.card-img-top {
    width: 100%;
    height: 30vw;
    object-fit: cover;
}
</style>
