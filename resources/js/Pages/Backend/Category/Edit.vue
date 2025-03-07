<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "Categories",
        icon: "view-list",
        bc: [
            {
                label: "categories",
                route: "admin.blog.categories.index"
            },
            {
                label: "edit"
            }
        ]
    }, () => page)
};
</script>
<script setup>
import { reactive, ref, toRefs } from "vue";
import Input from "@core/Components/Form/Input.vue";
import SaveFormButton from "@core/Components/Form/SaveFormButton.vue";
import Textarea from "@core/Components/Form/Textarea.vue";
import SeoForm from "@core/Components/Backend/SeoForm.vue";
import { useForm } from "@inertiajs/vue3";
import { __ } from "@core/Mixins/translations";
import { useStore } from "vuex";

const props = defineProps(["category", "slugPrefix", "errors"]);

const store = useStore();
const {category} = toRefs(props);
const selectedLocale = ref();
const inputValues = reactive({
    name: null,
    description: null
});
const form = useForm({
    ...category.value
});

function submit() {
    form.put(route("admin.blog.categories.update", {category: category.value.id}), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => store.dispatch("backend/addAlert", {type: "success", message: __("saved")}),
        onError: () => store.dispatch("backend/addAlert", {type: "error", message: form.errors})
    });
}
</script>
<template>
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("edit_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-skin-base ">
                <div class="col-span-1">
                    <p class="text-sm"
                       v-html="__('edit_summary')"></p>
                </div>
                <div class="col-span-2
                        border
                        dark:border-gray-600
                        bg-white dark:bg-gray-600
                        rounded-lg
                        shadow">
                    <div class="grid grid-cols-6 p-6">
                        <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4 mb-6">
                            <Input v-model="form"
                                   v-model:locale="selectedLocale"
                                   v-model:value="inputValues.name"
                                   :errors="form.errors"
                                   :label="__('name')"
                                   autofocus
                                   name="name"
                                   translation/>
                        </div>
                        <div class="col-span-6 mb-6">
                            <Textarea v-model="form"
                                      v-model:locale="selectedLocale"
                                      v-model:value="inputValues.description"
                                      :errors="form.errors"
                                      :label="__('description')"
                                      name="description"
                                      translation/>
                        </div>
                    </div>
                    <div class="rounded-b-lg overflow-hidden">
                        <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                            <SaveFormButton :form="form"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-skin-base  my-8">
                <SeoForm v-model:locale="selectedLocale"
                         v-model:seo="form.seo"
                         :description="inputValues.description"
                         :slug-prefix="slugPrefix"
                         :title="inputValues.name"/>
            </div>
        </div>
    </form>
</template>
