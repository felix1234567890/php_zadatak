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
        <span class="input-group-text" id="">
            <i class="fa fa-search"></i>
        </span>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchTerm: ""
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
