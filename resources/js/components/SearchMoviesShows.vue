<template>
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
</template>

<script>
export default {
    data() {
        return {
            searchTerm: "",
            type: "0"
        };
    },
    watch: {
        searchTerm(value) {
            console.log(value);
        }
    }
};
</script>

<style></style>
