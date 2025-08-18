<template>
    <div>
        <div v-if="event.banner_file_name" class="row justify-content-center">
            <div class="col-md-6">
                <img width="100%" class="mb-3 rounded shadow" :src="`/images/banners/${event.banner_file_name}`">
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <banner-component :event="event" />
            </div>
        </div>

        <component :is="Registration" :slots="slots" :event="event" />

        <DisclosurePrompt v-if="event.display_disclosure_prompt" :event="event"/>
    </div>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import DisclosurePrompt from "../../components/Registration/DisclosurePromptComponent.vue";

export default {
    props: {
        stepFolder: {
            type: String,
            required: true
        },
        slots: {
            required: true
        },
        event: {
            required: true
        }
    },
    components: {
        DisclosurePrompt
    },
    data() {
        return {
            Registration: null
        };
    },
    async created() {
        const registrationComponents = require.context(
            '../../components/Registration',
            true,
            /RegistrationComponent\.vue$/
        );

        const path = `./${this.stepFolder}/RegistrationComponent.vue`;
        if (registrationComponents.keys().includes(path)) {
            const component = registrationComponents(path);
            this.Registration = component.default || component;
        } else {
            console.error(`Component not found for path: ${path}`);
            console.log('Available:', registrationComponents.keys());
        }
    }
}
</script>

