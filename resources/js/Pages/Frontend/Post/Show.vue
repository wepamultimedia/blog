<script>
import MainLayout from "@pages/Vendor/Core/Frontend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, () => page)
};
</script>
<script setup>
import SocialShare from "@core/Components/SocialShare.vue";
import CategoriesList from "@/Vendor/Blog/Components/Frontend/Posts/CategoriesList.vue";
import CategoriesFlap from "@/Vendor/Blog/Components/Frontend/Posts/CategoriesFlap.vue";
import DatesList from "@/Vendor/Blog/Components/Frontend/Posts/DatesList.vue";
import DatesFlap from "@/Vendor/Blog/Components/Frontend/Posts/DatesFlap.vue";
import { ref } from "vue";
import oembed from "@core/Mixins/oembed";

const props = defineProps(["post", "categories", "dates"]);

const showCategoriesFlap = ref(false);
const showDatesFlap = ref(false);

let countedVisit = false;

function countVisit(event) {
    if (!countedVisit){
        countedVisit = true;
        axios.post(route("api.v1.blog.post.visit", {post: props.post.data.id}));
        window.document.removeEventListener("mousemove", countVisit);
        window.document.removeEventListener("touchmove", countVisit);
    }
}

onMounted(() => {
    window.document.addEventListener("mousemove", countVisit);
    window.document.addEventListener("touchmove", countVisit);
    new oembed().render();
});
</script>
<template>
    <div class="grid grid-cols-8 gap-2">
        <div>
            <div class="sticky top-10">
                <div class="col-span-full md:col-span-1 flex md:justify-center">
                    <div class="flex md:flex-col justify-start items-start">
                        <SocialShare :description="post.data.summary"
                                     :title="post.data.title"
                                     class=""
                                     network="Twitter"></SocialShare>
                        <SocialShare :description="post.data.summary"
                                     :title="post.data.title"
                                     class=""
                                     network="Facebook"></SocialShare>
                        <SocialShare :description="post.data.summary"
                                     :title="post.data.title"
                                     class=""
                                     network="Whatsapp"></SocialShare>
                        <SocialShare :description="post.data.summary"
                                     :title="post.data.title"
                                     class=""
                                     network="Telegram"></SocialShare>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-full md:col-span-5 lg:col-span-5 gap-8 max-w-3xl">
            <figure class="relative z-0">
                <img :alt="post.data.cover_alt"
                     :src="post.data.cover"
                     class="object-cover w-full aspect-video border-b border-skin-base">
                <div class="absolute bottom-4 left-0 flex gap-2">
                    <div class=" bg-gray-900 bg-opacity-60 px-4 py-1">
                        <h5 class="text-sm text-white">{{ post.data.category_name }}</h5>
                    </div>
                    <span>{{ post.data.start_at }}</span>
                </div>
            </figure>
            <h3 class="mt-6">{{ post.data.title }}</h3>
            <p class="mt-6" v-html="post.data.body"></p>
        </div>
        <div class="col-span-full md:col-span-2 lg:col-span-2 md:pl-8 mt-8 md:mt-0">
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
