<script setup>
import Flap from "@core/Components/Flap.vue";
import axios from "axios";
import {onBeforeMount, reactive} from "vue";

const props = defineProps(["modelValue"]);
const emits = defineEmits(["update:modelValue"]);

const surveys = reactive({
    flap: false,
    list: null,
    selected: null,
    open: () => {
        surveys.flap = true;
        if (!surveys.list) {
            surveys.getSurveys();
        }
    },
    loading: false,
    getSurveys: () => {
        surveys.loading = true;
        axios.get(route("api.surveys.questions.index")).then(response => {
            surveys.list = response.data.data;
        }).finally(() => surveys.loading = false);
    },
    getSurvey: id => {
        axios.get(route("api.surveys.questions.byId", {question: id})).then(response => {
            surveys.selected = response.data.data;
        });
    },
    select: question => {
        surveys.flap = false;
        emits("update:modelValue", question.id);
        surveys.selected = question;
    },
    remove: () => {
        surveys.selected = null;
        emits("update:modelValue", null);
    }
});

onBeforeMount(() => {
    if (props.modelValue) {
        surveys.getSurvey(props.modelValue);
    }
});
</script>

<template>
    <div class="">
        <h3 class="font-bold">{{ __("survey") }}</h3>
        <div class="my-4">
            <span v-if="surveys.selected"
                  class="">{{ surveys.selected?.question }}</span>

        </div>
        <button v-if="!surveys.selected"
                class="btn btn-secondary mt-2"
                type="button"
                @click.prevent="surveys.open">
            {{ __("select_survey") }}
        </button>
        <button v-else
                class="btn btn-secondary mt-2 mr-2"
                type="button"
                @click.prevent="surveys.open">
            {{ __("change_survey") }}
        </button>
        <button v-if="surveys.selected"
                class="btn bg-red-700 text-white mt-2"
                type="button"
                @click.prevent="surveys.remove">
            {{ __("remove_survey") }}
        </button>
    </div>
    <Flap v-model="surveys.flap"
          :title="__('surveys')"
          close-background
          md>
        <ul v-if="!surveys.loading">
            <li v-for="question in surveys.list">
                <button class="text-left hover:bg-skin-primary hover:text-white px-4 py-2"
                        @click.prevent="surveys.select(question)">
                    {{ question.question }}
                </button>
            </li>
        </ul>
        <div v-else class="flex justify-center">
            <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-500 fill-skin-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
        </div>
    </Flap>
</template>

<style scoped>

</style>
