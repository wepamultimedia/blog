<script>
import MainLayout from "@pages/Vendor/Core/Frontend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, () => page)
};
</script>
<script setup>
import Filters from "@pages/Vendor/Blog/Frontend/Post/Partials/Filters.vue";
import SocialShare from "@core/Components/SocialShare.vue";

const props = defineProps(["post", "categories", "dates"]);
</script>
<template>
    <div class="grid grid-cols-8 gap-2">
        <div>
            <div class="sticky top-20">
                <div class="col-span-full md:col-span-1 flex md:justify-center">
                    <div class="flex md:flex-col justify-start items-start">
                        <SocialShare :description="post.summary"
                                     :title="post.title"
                                     class=""
                                     network="Twitter"></SocialShare>
                        <SocialShare :description="post.summary"
                                     :title="post.title"
                                     class=""
                                     network="Facebook"></SocialShare>
                        <SocialShare :description="post.summary"
                                     :title="post.title"
                                     class=""
                                     network="Whatsapp"></SocialShare>
                        <SocialShare :description="post.summary"
                                     :title="post.title"
                                     class=""
                                     network="Telegram"></SocialShare>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-full md:col-span-5 lg:col-span-5 gap-8 max-w-3xl">
            <figure class="relative z-0">
                <img :alt="post.cover_alt"
                     :src="post.cover"
                     class="object-cover w-full aspect-video border-b border-skin-base">
                <div class="absolute bottom-4 left-0 bg-gray-900 bg-opacity-60 px-4 py-1">
                    <h5 class="text-sm text-white">{{ post.start_at }}</h5>
                </div>
            </figure>
            <h3 class="mt-6">{{ post.title }}</h3>
            <p class="mt-6">{{ post.body }}</p>
        </div>
        <div class="col-span-full md:col-span-2 lg:col-span-2 md:pl-8 mt-8 md:mt-0">
            <Filters :categories="categories"
                     :dates="dates"/>
        </div>
    </div>
</template>
<style scoped></style>
