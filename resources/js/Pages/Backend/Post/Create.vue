<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "posts",
        icon: "view-list",
        bc: [
            {
                label: "post",
                route: "admin.blog.posts.index"
            }, {label: "create"}
        ]
    }, () => page)
};
</script>
<script setup>
import { reactive, toRefs, ref } from "vue";
import Select from "@core/Components/Select.vue";
import Ckeditor from "@core/Components/Form/Ckeditor.vue";
import ToggleButton from "@core/Components/Form/ToggleButton.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Input from "@core/Components/Form/Input.vue";
import SaveFormButton from "@core/Components/Form/SaveFormButton.vue";
import Textarea from "@core/Components/Form/Textarea.vue";
import InputImage from "@core/Components/Form/InputImage.vue";
import SeoForm from "@core/Components/Backend/SeoForm.vue";
import { useForm } from "@inertiajs/vue3";
import { __ } from "@core/Mixins/translations";
import { useStore } from "vuex";

const props = defineProps(["categories", "post", "errors"]);
const {
          post,
          categories,
          errors
      } = toRefs(props);

const store = useStore();
const selectedLocale = ref();
const body = ref("");
const form = useForm({
    video_cover: null,
    cover: null,
    category_id: null,
    start_at: null,
    translations: {},
    ...post.value
});
const values = reactive({
    title: null,
    description: null,
    cover: {url: null, name: null, alt_name: null},
    cover_title: null,
    cover_alt: null
});

function submit() {
    form.post(route("admin.blog.posts.store"), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => store.dispatch("backend/addAlert", {type: "success", message: __("saved")}),
        onError: () => store.dispatch("backend/addAlert", {type: "error", message: errors.value})
    });
}
</script>
<template>
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("create_title") }}</span>
    </div>
    <form class="pb-8"
          @submit.prevent="submit">
        <div class="text-skin-base

                    border
                    dark:border-gray-600
                    bg-white dark:bg-gray-600
                    rounded-lg
                    shadow">
            <div class="grid grid-cols-12 divide-y xl:divide-x divide-gray-300 dark:divide-gray-700">
                <!-- title, summary and body-->
                <div class="p-6 col-span-full xl:col-span-8">
                    <div class="mb-6">
                        <Input v-model="form"
                               v-model:locale="selectedLocale"
                               v-model:value="values.title"
                               :errors="errors"
                               :label="__('title')"
                               autofocus
                               name="title"
                               required
                               translation/>
                    </div>
                    <div class="mb-6">
                        <Input v-model="form"
                               v-model:locale="selectedLocale"
                               v-model:value="values.description"
                               :errors="errors"
                               :label="__('summary')"
                               name="summary"
                               required
                               translation/>
                    </div>
                    <div class="mb-6">
                        <div class="mt-1"
                             style="--ck-border-radius: 0.50rem">
                            <Ckeditor v-model="form"
                                      v-model:locale="selectedLocale"
                                      :errors="errors"
                                      :label="__('body')"
                                      name="body"
                                      required
                                      translation></Ckeditor>
                        </div>
                    </div>
                </div>
                <!-- draf, date, category and cover -->
                <div class="col-span-full xl:col-span-4 grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-1 divide-y lg:divide-x lg:divide-y-0 xl:divide-y xl:divide-x-0 divide-gray-300 dark:divide-gray-700 gap-4">
                    <!-- draft, date and category -->
                    <div class="p-6">
                        <div class="mb-6">
                            <label class="text-sm">{{ __("draft") }}</label>
                            <ToggleButton v-model="form.draft"/>
                        </div>
                        <div class="mb-6">
                            <label class="text-sm">{{ __("start_at") }} *</label>
                            <Datepicker id="date"
                                        v-model="form.start_at"
                                        :auto-apply="true"
                                        :close-on-auto-apply="true"
                                        :enableTimePicker="false"
                                        class="mt-1"
                                        format="d/M/yyyy"
                                        locale="es"></Datepicker>
                            <div v-if="errors['start_at']"
                                 class="text-red-300 text-sm mt-1">* {{ errors["start_at"] }}
                            </div>
                        </div>
                        <div>
                            <Select v-model="form.category_id"
                                    :errors="errors"
                                    :label="__('select_category')"
                                    :options="categories"
                                    name="category_id"
                                    option-label="name"
                                    reduce
                                    required></Select>
                        </div>
                    </div>
                    <!-- cover -->
                    <div class="p-6">
                        <div class="mb-6">
                            <Textarea v-model="form"
                                      v-model:locale="selectedLocale"
                                      :errors="errors"
                                      :label="__('video_cover')"
                                      :legend="__('video_cover_legend')"
                                      name="video_cover"/>
                        </div>
                        <div class="sm:w-1/2 lg:w-full mb-6">
                            <InputImage v-model="form.cover"
                                        v-model:alt="values.cover_alt"
                                        v-model:image="values.cover"
                                        v-model:title="values.cover_title"
                                        :errors="errors"
                                        :label="__('cover_image')"
                                        name="cover"/>
                        </div>
                        <div>
                            <Input v-model="form"
                                   v-model:locale="selectedLocale"
                                   v-model:value="values.cover_title"
                                   :errors="errors"
                                   :label="__('cover_title')"
                                   name="cover_title"
                                   required
                                   translation/>
                        </div>
                        <div class="mt-4">
                            <Textarea v-model="form"
                                      v-model:locale="selectedLocale"
                                      v-model:value="values.cover_alt"
                                      :errors="errors"
                                      :label="__('cover_alt')"
                                      name="cover_alt"
                                      required
                                      translation/>
                        </div>
                    </div>
                </div>
            </div>
            <!-- seo -->
            <div class="rounded-b-lg overflow-hidden">
                <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                    <SaveFormButton :form="form"/>
                </div>
            </div>
        </div>
        <div class="my-8">
            <h2 class="font mb-4">{{ __("seo") }}</h2>
            <SeoForm v-model:seo="form.seo"
                     :description="values.description"
                     :image="values.cover"
                     :locale="selectedLocale"
                     :title="values.title"
                     article-type="blog_entry"
                     autocomplete/>
        </div>
    </form>
</template>
<style>
.ck-editor__editable {
    @apply min-h-[260px] max-h-[800px]
}
</style>
