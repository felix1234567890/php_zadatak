<template>
    <div class="row">
        <span class="py-3">
            <router-link to="/">
                <i class="fas fa-arrow-circle-left fa-3x"></i>
            </router-link>
        </span>

        <div class="col-xs-12 col-sm-6">
            <img
                :src="
                    movieOrShow.poster_path
                        ? `https://image.tmdb.org/t/p/w500/${movieOrShow.poster_path}`
                        : 'http://via.placeholder.com/500x750'
                "
                class="poster-img"
            />
        </div>
        <div class="col-xs-12 col-sm-6">
            <p>
                <strong>Title:</strong>
                {{ movieOrShow.title || movieOrShow.name }}
            </p>
            <div v-if="movieOrShow.title !== movieOrShow.original_title">
                <p>
                    <strong>Original title:</strong>
                    {{ movieOrShow.original_title }}
                </p>
            </div>
            <div v-if="movieOrShow.original_language !== 'en'">
                <p>
                    <strong>Original language:</strong>
                    {{ movieOrShow.original_language }}
                </p>
            </div>
            <p>
                <strong>Description:</strong>
                {{ movieOrShow.overview }}
            </p>
            <p>
                <strong>Genres:</strong>
                {{ movieOrShow.genres && movieOrShow.genres.join(", ") }}
            </p>
            <p v-if="movieOrShow.created_by">
                <strong>Created By:</strong>
                {{
                    movieOrShow.created_by && movieOrShow.created_by.join(", ")
                }}
            </p>
            <p v-if="movieOrShow.release_date">
                <strong>Release date:</strong>
                {{ movieOrShow.release_date }}
            </p>
            <p v-if="movieOrShow.runtime">
                <strong>Runtime:</strong>
                {{ movieOrShow.runtime }} minutes
            </p>
            <p v-if="movieOrShow.episode_run_time">
                <strong>Runtime:</strong>
                {{ movieOrShow.episode_run_time[0] }} minutes
            </p>
            <p v-if="movieOrShow.number_of_seasons">
                <strong>Number of seasons:</strong>
                {{ movieOrShow.number_of_seasons }}
            </p>
            <p v-if="movieOrShow.number_of_episodes">
                <strong>Number of episodes:</strong>
                {{ movieOrShow.number_of_episodes }}
            </p>
            <p v-if="movieOrShow.networks">
                <strong>Networks:</strong>
                {{ movieOrShow.networks && movieOrShow.networks.join(", ") }}
            </p>
            <p>
                <strong>Vote average:</strong>
                {{ movieOrShow.vote_average }}
            </p>
            <p>
                <strong>Popularity:</strong>
                {{ movieOrShow.popularity }}
            </p>
            <p>
                <strong>Production companies:</strong>
                {{
                    movieOrShow.production_companies &&
                        movieOrShow.production_companies.join(", ")
                }}
            </p>
            <p v-if="movieOrShow.production_countries">
                <strong>Production countries:</strong>
                {{
                    movieOrShow.production_countries &&
                        movieOrShow.production_countries.join(", ")
                }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            movieOrShow: {}
        };
    },
    created() {
        axios.get(`/api/details/${this.$route.params.id}`).then(response => {
            if (response.data.type === "movie") {
                response.data[0].production_countries = response.data[0].production_countries.map(
                    country => country.name
                );
            } else {
                response.data[0].created_by = response.data[0].created_by.map(
                    author => author.name
                );
                response.data[0].networks = response.data[0].networks.map(
                    network => network.name
                );
            }
            response.data[0].genres = response.data[0].genres.map(
                genre => genre.name
            );
            response.data[0].production_companies = response.data[0].production_companies.map(
                company => company.name
            );
            this.movieOrShow = response.data[0];
        });
    }
};
</script>

<style>
@media (min-width: 576px) {
    .poster-img {
        height: 100%;
        width: 36vw;
        max-width: 600px;
    }
}
</style>
