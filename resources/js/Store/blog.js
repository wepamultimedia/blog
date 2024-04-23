import axios from "axios";

export default {
    namespaced: true,
    state: {
        latestPosts: [],
        loadingLatestPosts: false,
        maxLatestPostsRequested: 0
    }, actions: {
        loadLatestPosts({commit, state}, payload) {
            if (!state.latestPosts.length && !state.loadingLatestPosts) {
                state.loadingLatestPosts = true;

                commit("SET_MAX_LATEST_POSTS_REQUESTED", payload.number);

                axios.get(route("api.v1.blog.posts.latest", {number: payload.number + 1})).then(response => {
                    commit("SET_LATEST_POSTS", response.data.data);
                    state.loadingLatestPosts = false;
                });
            }
        }
    }, getters: {
        maxLatestPostsRequested: state => state.maxLatestPostsRequested,
        latestPostsExcept: state => except_id => state.latestPosts.filter(post => {
            if (post.id !== except_id) {
                return post;
            }
        }),
        latestPosts: state => state.latestPosts
    },
    mutations: {
        SET_LATEST_POSTS(state, value) {
            state.latestPosts = value;
        },
        SET_MAX_LATEST_POSTS_REQUESTED(state, value) {
            state.maxLatestPostsRequested = value;
        }
    }
};
