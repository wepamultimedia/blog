<script setup>
import Flap from "@core/Components/Flap.vue";
import { Link } from "@inertiajs/vue3";
import { computed, defineEmits } from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    dates: {
        type: [Object, Array],
        required: true
    },
    ulClass: String,
    liClass: String,
    activeClass: {
        type: String,
        default: 'font-bold'
    }
});

const emits = defineEmits(["update:show"]);

const proxyShow = computed({
    get() {
        return props.show;
    },
    set(value) {
        emits("update:show", value);
    }
});
</script>
<template>
    <Flap v-model="proxyShow"
          close-background>
        <!-- Categories -->
        <nav>
            <h2 class="text-xl border-b-2 border-gray-200 dark:border-gray-700 pb-2 mb-4 font-bold">{{
                    __("dates")
                }}
            </h2>
            <ul class="divide-y">
                <li v-for="item in dates">
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
    </Flap>
</template>
<style scoped></style>
