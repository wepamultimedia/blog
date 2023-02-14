<script>
import MainLayout from "@pages/Core/Frontend/Layouts/MainLayout/MainLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default {
    layout: (h, page) => h(MainLayout, () => page)
};
</script>
<script setup>
import { usePage } from "@inertiajs/inertia-vue3";
import Pagination from "@core/Components/Pagination.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Filters from "@/Pages/Blog/Frontend/Post/Partials/Filters.vue";

const props = defineProps(["posts", "categories", "category", "dates"]);
</script>
<template>
    <div class="grid grid-cols-8">
        <!-- Posts -->
        <div class="col-span-8 md:col-span-5 lg:col-span-6 gap-8">
            <!-- Category info -->
            <div v-if="category"
                 class="col-span-full mb-8">
                <h1>{{ category.name }}...</h1>
                <p>{{ category.description }}...</p>
            </div>
            <!-- Posts -->
            <div class="grid sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-8">
                <a :href="'/' + post.seo.slug" v-for="post in posts.data"
                     class="cursor-pointer">
                    <figure class="relative" style="padding-bottom: 70%">
                        <img :alt="post.cover_alt"
                             :src="post.cover"
                             class="h-full w-full absolute object-cover">
                        <div class="absolute bottom-4 left-0 bg-gray-900 bg-opacity-60 px-4 py-1">
                            <h5 class="text-sm text-white">{{ post.start_at }}</h5>
                        </div>
                    </figure>
                    <h2 class="mt-3">{{ post.title }}</h2>
                    <h4 class="mt-2 text-skin-base dark:text-skin-base-dark">{{ post.summary }}</h4>
                </a>
            </div>
            <!-- Pagination -->
            <Pagination :links="posts.links"
                        class="my-8"/>
        </div>
        <!-- Sidebar -->
        <div class="col-span-full md:col-span-3 lg:col-span-2 md:pl-8">
            <Filters :categories="categories"
                     :category="category"
                     :dates="dates"/>
        </div>
    </div>
</template>
<style scoped></style>
