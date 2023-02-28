<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    category: {
        type: [Object, Array]
    },
    categories: {
        type: [Array, Object],
        required: true
    },
    limit: Number,
    ulClass: String,
    liClass: String,
    activeClass: {
        type: String,
        default: 'font-bold'
    }
});

const categoriesList = ref();

if (props.limit) {
    categoriesList.value = props.categories.slice(0, props.limit);
} else {
    categoriesList.value = props.categories;
}
</script>
<template>
    <nav>
        <ul :class="[ulClass]"
            class="divide-y">
            <li v-for="item in categoriesList"
                :class="[liClass]">
                <Link :class="{[activeClass]: category?.id === item.id}"
                      :href="'/' + item.seo.slug"
                      as="button"
                      class="py-1"
                      preserve-scroll
                      type="button">
                    {{ item.name }}
                </Link>
            </li>
        </ul>
    </nav>
</template>
<style scoped></style>
