<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";

const props = defineProps({
    number: {
        type: Number,
        required: true
    }
});

const posts = ref();
const loading = ref(false);

function getPosts() {
    axios.get(route("api.v1.blog.posts.latest", {number: props.number})).then(response => {
        posts.value = response.data;
    });
}

onMounted(() => getPosts());
</script>
<template>
    <slot v-if="posts"
          :posts="posts"></slot>
</template>
<style scoped></style>
