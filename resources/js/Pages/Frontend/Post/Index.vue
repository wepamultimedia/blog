<script>
import MainLayout from "@pages/Vendor/Core/Frontend/Layouts/MainLayout/MainLayout.vue";
import { Link } from "@inertiajs/vue3";

export default {
    layout: (h, page) => h(MainLayout, () => page)
};
</script>
<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import Pagination from "@core/Components/Pagination.vue";
import CategoriesList from "@/Vendor/Blog/Components/Frontend/Posts/CategoriesList.vue";
import CategoriesFlap from "@/Vendor/Blog/Components/Frontend/Posts/CategoriesFlap.vue";
import DatesList from "@/Vendor/Blog/Components/Frontend/Posts/DatesList.vue";
import DatesFlap from "@/Vendor/Blog/Components/Frontend/Posts/DatesFlap.vue";

const props = defineProps(["posts", "categories", "category", "dates"]);

const showCategoriesFlap = ref(false);
const showDatesFlap = ref(false);
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
                <a v-for="post in posts.data"
                   :href="'/' + post.slug"
                   class="cursor-pointer">
                    <figure class="relative"
                            style="padding-bottom: 70%">
                        <img :alt="post.cover_alt"
                             :src="post.cover"
                             class="h-full w-full absolute object-cover">
                        <div class="absolute bottom-4 left-0 flex gap-2">
                            <div class=" bg-gray-900 bg-opacity-60 px-4 py-1">
                                <h5 class="text-sm text-white">{{ post.category_name }}</h5>
                            </div>
                            <span>{{ post.start_at }}</span>
                        </div>
                    </figure>
                    <h2 class="mt-3">{{ post.title }}</h2>
                    <h4 class="mt-2 text-skin-base ">{{ post.summary }}</h4>
                </a>
            </div>
            <!-- Pagination -->
            <Pagination :links="posts.links"
                        class="my-8"/>
        </div>
        <!-- Sidebar -->
        <div class="col-span-full md:col-span-3 lg:col-span-2 md:pl-8">
            <div class="sticky top-10">
                <div>
                    <h2 class="text-xl border-b-2 border-gray-200 dark:border-gray-700 pb-2 mb-4 font-bold">{{
                            __("categories")
                        }}
                    </h2>
                    <CategoriesList :categories="categories"
                                    :category="category"
                                    :limit="5"
                                    active-class="font-bold"/>
                    <button class="pt-4 text-gray-600 dark:text-gray-400 w-full text-left font-bold text-sm uppercase"
                            type="button"
                            @click.stop="showCategoriesFlap = true">
                        {{ __("view_more") }}...
                    </button>
                </div>
                <div class="mt-4">
                    <h2 class="text-xl border-b-2 border-gray-200 dark:border-gray-700 pb-2 mb-4 font-bold">
                        {{ __("dates") }}
                    </h2>
                    <DatesList :dates="dates"
                               :limit="5"/>
                    <button class="pt-4 text-gray-600 dark:text-gray-400 w-full text-left font-bold text-sm uppercase"
                            type="button"
                            @click.stop="showDatesFlap = true">
                        {{ __("view_more") }}...
                    </button>
                </div>
                <div>
                    <Link :href="route('blog')"
                          as="button"
                          class="py-2 col-span-full mt-4 text-sm justify-center uppercase font-bold btn btn-secondary"
                          preserve-scroll
                          type="button">
                        {{ __("clear_filters") }}
                    </Link>
                </div>
            </div>
        </div>
    </div>
    <CategoriesFlap v-model:show="showCategoriesFlap"
                    :categories="categories"
                    :category="category"></CategoriesFlap>
    <DatesFlap v-model:show="showDatesFlap"
               :dates="dates"></DatesFlap>
</template>
<style scoped></style>
