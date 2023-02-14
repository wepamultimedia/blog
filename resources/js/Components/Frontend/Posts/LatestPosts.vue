<script setup>
import { ref } from "vue";

const props = defineProps({
    number: {
        type: Number,
        required: true
    }
});

const posts = ref();
const loading = ref(false);

function getPosts() {
    axios.get(route("api.blog.posts.latest", {number: props.number})).then(response => {
        posts.value = response.data
    });
}

getPosts();
</script>
<template>
    <slot v-if="posts" :posts="posts"></slot>
</template>
<style scoped></style>
