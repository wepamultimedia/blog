<script setup>
import axios from "axios";
import {onMounted, ref} from "vue";

const props = defineProps({
    number: {
        type: Number,
        default: 10,
        required: true
    },
    exceptId: {
        type: Number
    }
});

const posts = ref([]);

function getLatestPosts() {
    let data = {number: props.number};
    if (props.exceptId) {
        data.except_id = props.exceptId;
    }
    axios.get(route("api.v1.blog.posts.latest", data)).then(response => {
        posts.value = response.data.data;
    });
}

onMounted(() => {
    getLatestPosts();
});
</script>
<template>
    <slot :latestPosts="posts"></slot>
</template>
<style scoped></style>
