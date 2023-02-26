<script setup>
import { nextTick, onMounted, reactive, ref } from "vue";
import Flap from "@core/Components/Flap.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps(["categories", "category", "dates"]);

const filters = ref();
const filterTop = ref();
const filtersFlap = reactive({
    show: false,
    type: null,
    categories: [],
    dates: []
});
const resetFiltersFlap = () => {
    filtersFlap.show = false;
    filtersFlap.type = null;
};
const viewMoreCategories = () => {
    filtersFlap.show = true;
    filtersFlap.type = "categories";
    axios.get(route("api.v1.blog.categories.index")).then(response => {
        filtersFlap.categories = response.data;
    });
};
const viewMoreDates = () => {
    filtersFlap.show = true;
    filtersFlap.type = "dates";
    axios.get(route("api.v1.blog.posts.dates")).then(response => {
        filtersFlap.dates = response.data;
    });
};

onMounted(() => {
    nextTick(() => {
        filterTop.value = filters.value.offsetTop;
    });
});
</script>
<template>
    <div ref="filters"
         class="grid sm:grid-cols-2 md:grid-cols-1 gap-16">
        <!-- Categories -->
        <nav>
            <h2 class="text-xl border-b-2 border-gray-200 dark:border-gray-700 pb-2 mb-4 font-bold">{{
                    __("categories")
                }}
            </h2>
            <ul class="divide-y dark:divide-gray-700">
                <li v-for="categoryItem in categories">
                    <Link :class="{'font-bold': category?.id === categoryItem.id}"
                          :href="'/' + categoryItem.seo.slug"
                          as="button"
                          class="py-1 text-gray-600 dark:text-gray-400 w-full text-left"
                          preserve-scroll
                          type="button">
                        {{ categoryItem.name }}
                    </Link>
                </li>
                <li>
                    <button class="pt-4 text-gray-600 dark:text-gray-400 w-full text-left font-bold text-sm uppercase"
                            type="button"
                            @click.stop="viewMoreCategories()">
                        {{ __("view_more") }}...
                    </button>
                </li>
            </ul>
        </nav>
        <!-- Dates -->
        <nav>
            <h2 class="text-xl border-b-2 border-gray-200 dark:border-gray-700 pb-2 mb-4 font-bold">{{ __("dates") }}</h2>
            <ul class="divide-y dark:divide-gray-700">
                <li v-for="date in dates">
                    <Link :class="{'font-bold': date.active}"
                          :href="route('blog', {start_at: date.date})"
                          as="button"
                          class="py-1 text-gray-600 dark:text-gray-400 w-full text-left"
                          preserve-scroll
                          type="button">
                        {{ date.label }}
                    </Link>
                </li>
                <li>
                    <button class="pt-4 text-gray-600 dark:text-gray-400 w-full text-left text-sm font-bold uppercase"
                            type="button"
                            @click="viewMoreDates()">
                        {{ __("view_more") }}...
                    </button>
                </li>
            </ul>
        </nav>
        <Link :class="{'font-bold': !category}"
              :href="route('blog')"
              as="button"
              class="py-2 col-span-full mt-4 text-sm justify-center uppercase font-bold btn btn-secondary"
              preserve-scroll
              type="button">
            {{ __("clear_filters") }}
        </Link>
    </div>
    <!-- Filters Flap -->
    <Flap v-model="filtersFlap.show"
          close-background>
        <!-- Categories -->
        <nav v-if="filtersFlap.type === 'categories'">
            <h2 class="text-xl border-b-2 border-gray-200 dark:border-gray-700 pb-2 mb-4 font-bold">{{
                    __("categories")
                }}
            </h2>
            <ul class="divide-y dark:divide-gray-700">
                <li v-for="categoryItem in filtersFlap.categories">
                    <Link :class="{'font-bold': category?.id === categoryItem.id}"
                          :href="'/' + categoryItem.seo.slug"
                          as="button"
                          class="py-1 text-gray-600 dark:text-gray-400 w-full text-left"
                          preserve-scroll
                          preserve-state
                          type="button"
                          @click="resetFiltersFlap()">
                        {{ categoryItem.name }}
                    </Link>
                </li>
            </ul>
        </nav>
        <!-- Dates -->
        <nav v-if="filtersFlap.type === 'dates'">
            <h2 class="text-xl border-b-2 border-gray-200 dark:border-gray-700 pb-2 mb-4 font-bold">{{ __("dates") }}</h2>
            <ul class="divide-y dark:divide-gray-700">
                <li v-for="date in filtersFlap.dates">
                    <Link :class="{'font-bold': date.active}"
                          :href="route('blog', {start_at: date.date})"
                          as="button"
                          class="py-1 text-gray-600 dark:text-gray-400 w-full text-left"
                          preserve-scroll
                          preserve-state
                          type="button"
                          @click="resetFiltersFlap()">
                        {{ date.label }}
                    </Link>
                </li>
            </ul>
        </nav>
    </Flap>
</template>
<style scoped></style>
