<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    dates: {
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

const datesList = ref();

if (props.limit) {
    datesList.value = props.dates.slice(0, props.limit);
} else {
    datesList.value = props.dates;
}
</script>
<template>
    <nav>
        <ul :class="[ulClass]"
            class="divide-y">
            <li v-for="item in datesList"
                :class="[liClass]">
                <Link :class="{[activeClass]: item.active}"
                      :href="route('blog.date', {start_at: item.date})"
                      as="button"
                      class="py-1"
                      preserve-scroll
                      type="button">
                    {{ item.label }}
                </Link>
            </li>
        </ul>
    </nav>
</template>
<style scoped></style>
