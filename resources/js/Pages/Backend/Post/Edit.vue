<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "posts",
        icon: "view-list",
        bc: [{label: "post", route: "admin.blog.posts.index"}, {label: "edit"}]
    }, () => page)
};
</script>
<script setup>
import SelectSurvey from "@js/Vendor/Blog/Backend/Posts/SelectSurvey.vue";
import {reactive, toRefs, ref, onBeforeMount} from "vue";
import Select from "@core/Components/Select.vue";
import Ckeditor from "@core/Components/Form/Ckeditor.vue";
import ToggleButton from "@core/Components/Form/ToggleButton.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Input from "@core/Components/Form/Input.vue";
import Textarea from "@core/Components/Form/Textarea.vue";
import InputImage from "@core/Components/Form/InputImage.vue";
import SeoForm from "@core/Components/Backend/SeoForm.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import SaveFormButton from "@core/Components/Form/SaveFormButton.vue";
import {__} from "@core/Mixins/translations";
import {useStore} from "vuex";

const props = defineProps(["post", "categories", "slugPrefix", "hasSurveys", "errors", "loadSurveys"]);
const {categories, post} = toRefs(props);

const store = useStore();
const body = ref("");
const form = useForm({...post.value});
const values = reactive({
    title: "",
    description: "",
    cover_url: props.post.cover,
    cover_title: "",
    cover_alt: ""
});

function submit() {
    form.put(route("admin.blog.posts.update", {post: post.value.id}), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => store.dispatch("backend/addAlert", {type: "success", message: __("saved")}),
        onError: () => store.dispatch("backend/addAlert", {type: "error", message: form.errors})
    });
}


onBeforeMount(() => {
    store.dispatch("backend/formLocale", usePage().props.default.locale);
});
</script>
<template>
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("edit_title") }}</span>
        <ToggleButton v-model="form.draft"
                      :label="__('draft')"
                      class="mr-4"/>
    </div>
    <form class="pb-8"
          @submit.prevent="submit">
        <div class="text-skin-base border dark:border-gray-600 bg-white dark:bg-gray-600 rounded-lg shadow">
            <div class="grid grid-cols-12 divide-y xl:divide-y-0 xl:divide-x divide-gray-300 dark:divide-gray-700">
                <!-- title, summary and body-->
                <div class="p-6 col-span-full xl:col-span-8">
                    <div class="mb-6">
                        <Input v-model="form"
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
                    <div class="grid grid-cols-1 divide-y divide-gray-300 dark:divide-gray-700">
                        <div class="p-6">
                            <label class="text-sm font-bold">{{ __("start_at") }} *</label>
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
                        <div class="p-6">
                            <Select v-model="form.category_id"
                                    :errors="errors"
                                    :label="__('select_category')"
                                    :options="categories"
                                    name="category_id"
                                    option-label="name"
                                    reduce
                                    required></Select>
                        </div>
                        <div v-if="loadSurveys"
                             class="p-6">
                            <SelectSurvey v-model="form.survey_id"></SelectSurvey>
                        </div>
                    </div>
                    <!-- cover -->
                    <div class="grid grid-cols-1 divide-y divide-gray-300 dark:divide-gray-700">
                        <div class="p-6">
                            <h3>{{ __("cover_image") }}</h3>
                            <div class="mb-4">
                                <InputImage v-model="form.cover"
                                            v-model:alt_name="values.cover_alt"
                                            v-model:title="values.cover_title"
                                            v-model:url="values.cover_url"
                                            :errors="errors"
                                            name="cover"/>
                            </div>
                            <div class="mb-4">
                                <Input v-model="form"
                                       v-model:value="values.cover_title"
                                       :errors="errors"
                                       :label="__('cover_title')"
                                       name="cover_title"
                                       required
                                       translation/>
                            </div>
                            <div>
                                <Textarea v-model="form"
                                          v-model:value="values.cover_alt"
                                          :errors="errors"
                                          :label="__('cover_alt')"
                                          name="cover_alt"
                                          required
                                          translation/>
                            </div>
                        </div>
                        <div class="p-6">
                            <Textarea v-model="form"
                                      :errors="errors"
                                      :label="__('video_cover')"
                                      :legend="__('video_cover_legend')"
                                      name="video_cover"/>
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
                     :image="values.cover_url"
                     :image-alt="values.cover_alt"
                     :image-title="values.cover_title"
                     :slug-prefix="slugPrefix"
                     :title="values.title"/>
        </div>
    </form>
</template>
<style>
.ck-editor__editable {
    @apply min-h-[460px] max-h-[800px]
}
</style>
