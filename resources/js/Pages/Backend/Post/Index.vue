<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "posts",
        icon: "view-list",
        bc: [{label: "posts"}]
    }, () => page)
};
</script>
<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "@core/Components/Table.vue";
import ToggleButton from "@core/Components/Form/ToggleButton.vue";
import { Inertia } from "@inertiajs/inertia";
import Icon from "@core/Components/Heroicon.vue";

defineProps(["categories", "posts"]);

function draft(item) {
    Inertia.put(route("admin.blog.posts.draft", {
        post: item.id,
        draft: item.draft ? 1 : 0
    }), {},{
        preserveState: true,
        preserveScroll: true
    });
}

const updatePosition = (item, position) => {
    Inertia.put(route("admin.blog.posts.position", {
        post: item.id,
        position: position
    }), {},{
        preserveState: true,
        preserveScroll: true
    });
}
</script>
<template>
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden my-6">
        <span class="text-skin-base dark:text-skin-base-dark font-medium text-xl">{{ __("posts_list") }}</span>
        <Link :href="route('admin.blog.posts.create')"
              as="button"
              class="btn btn-success text-sm"
              type="button">{{ __("create") }}
        </Link>
    </div>
    <div class="w-full
                    bg-white dark:bg-gray-700
                    overflow-hidden
                    shadow
                    text-skin-base dark:text-skin-base-dark
                    rounded-lg
                    mb-20">
        <Table :columns="['title', {name: 'draft', show: 'md'}, 'position']"
               :data="posts"
               delete-route="admin.blog.posts.destroy"
               divide-x
               edit-route="admin.blog.posts.edit"
               even
               search-route="admin.blog.posts.index">
            <template #col-content-draft="{item}">
                <ToggleButton :key="item.id + 'component'"
                              v-model="item.draft"
                              @change="draft(item)"/>
            </template>
            <template #col-content-position="{item}">
                <div class="flex items-center justify-start">
                    <div class="inline-flex"
                         role="group">
                        <button class="rounded-l inline-block px-1 py-2.5 bg-gray-400 text-white font-medium text-xs leading-tight uppercase hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-0 active:bg-gray-800 transition duration-150 ease-in-out"
                                type="button"
                                @click="updatePosition(item, item.position + 1)">
                            <Icon class="m-0 fill-white h-4 w-4"
                                  icon="chevron-up"></Icon>
                        </button>
                        <span class="inline-block px-2 py-2.5 bg-gray-400 text-white font-medium text-xs leading-tight uppercase"
                              type="button">
                            {{ item.position }}
                        </span>
                        <button class="rounded-r inline-block px-1 py-2.5 bg-gray-400 text-white font-medium text-xs leading-tight uppercase hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-0 active:bg-gray-800 transition duration-150 ease-in-out"
                                type="button"
                                @click="updatePosition(item, item.position - 1)">
                            <Icon class="m-0 fill-white h-4 w-4"
                                  icon="chevron-down"></Icon>
                        </button>
                    </div>
                </div>
            </template>
        </Table>
    </div>
</template>
<style lang="scss"
       scoped></style>
