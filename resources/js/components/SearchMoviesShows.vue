<template>
    <div>
        <div class="input-group flex-nowrap py-5">
            <input
                type="text"
                class="form-control"
                placeholder="Search movies or tv shows"
                aria-label="Username"
                aria-describedby="addon-wrapping"
                v-model="searchTerm"
            />

            <span class="input-group-text">
                <select class="form-select" v-model="type">
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
                class="col-md-4 col-sm-6 align-self-center"
                v-for="item in searchData"
                :key="item.id"
            >
                <div class="card h-100 m-2">
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
export default {
    data() {
        return {
            searchTerm: "",
            type: "both",
            searchData: []
        };
    },
    // watch: {
    // searchTerm(value) {
    //     if (value.length < 3) {
    //         this.searchData = [];
    //         return;
    //     }
    //     axios
    //         .get("/api/search", {
    //             params: { searchTerm: this.searchTerm, type: this.type }
    //         })
    //         .then(response => {
    //             console.log(response.data);
    //             this.searchData = response.data;
    //         })
    //         .catch(error => console.log(error));
    // },
    // type(value) {
    //     if (this.searchTerm.length < 3) {
    //         this.searchData = [];
    //         return;
    //     }
    //     axios
    //         .get("/api/search", {
    //             params: { searchTerm: this.searchTerm, type: this.type }
    //         })
    //         .then(response => {
    //             console.log(response.datad);
    //             this.searchData = response.data;
    //         })
    //         .catch(error => console.log(error));
    // }
    // },
    methods: {
        shortenDescription(text, len = 200) {
            console.log(text);
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
                    this.searchData = response.data;
                })
                .catch(error => console.log(error));
        }
    }
};
</script>

<style></style>
