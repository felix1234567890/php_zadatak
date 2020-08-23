<template>
    <div class="row">
        <span class="py-3">
            <router-link to="/">
                <i class="fas fa-arrow-circle-left fa-3x"></i>
            </router-link>
        </span>

        <div class="col-md-6">
            <img
                :src="
                    movie.poster_path
                        ? `https://image.tmdb.org/t/p/w500/${movie.poster_path}`
                        : 'http://via.placeholder.com/500x750'
                "
                class="h-100 w-100"
            />
        </div>
        <div class="col-md-6">
            <h4>Genres: {{ movie.genres && movie.genres.join(", ") }}</h4>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            movie: {}
        };
    },
    created() {
        axios.get(`/api/details/${this.$route.params.id}`).then(response => {
            response.data.genres = response.data.genres.map(
                genre => genre.name
            );
            this.movie = response.data;
        });
    }
};
</script>

<style></style>
