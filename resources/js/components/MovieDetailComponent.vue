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
            />
        </div>
        <div class="col-md-6">
            <p>
                <strong>Title:</strong>
                {{ movie.title }}
            </p>
            <div v-if="movie.title !== movie.original_title">
                <p>
                    <strong>Original title:</strong>
                    {{ movie.original_title }}
                </p>
            </div>
            <div v-if="movie.original_language !== 'en'">
                <p>
                    <strong>Original language:</strong>
                    {{ movie.original_language }}
                </p>
            </div>
            <p>
                <strong>Description:</strong>
                {{ movie.overview }}
            </p>
            <p>
                <strong>Genres:</strong>
                {{ movie.genres && movie.genres.join(", ") }}
            </p>
            <p>
                <strong>Release date:</strong>
                {{ movie.release_date }}
            </p>
            <p>
                <strong>Runtime:</strong>
                {{ movie.runtime }} mins
            </p>
            <p>
                <strong>Vote average:</strong>
                {{ movie.vote_average }}
            </p>
            <p>
                <strong>Popularity:</strong>
                {{ movie.popularity }}
            </p>
            <p>
                <strong>Production companies:</strong>
                {{
                    movie.production_companies &&
                        movie.production_companies.join(", ")
                }}
            </p>
            <p>
                <strong>Production countries:</strong>
                {{
                    movie.production_countries &&
                        movie.production_countries.join(", ")
                }}
            </p>
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
            console.log(response.data);
            response.data.genres = response.data.genres.map(
                genre => genre.name
            );
            response.data.production_companies = response.data.production_companies.map(
                company => company.name
            );
            response.data.production_countries = response.data.production_countries.map(
                country => country.name
            );
            this.movie = response.data;
        });
    }
};
</script>

<style></style>
