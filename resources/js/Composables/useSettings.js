import {computed} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

const useSettings = () => {
    const settings = computed(() => {
        return usePage().props.value.mixpost.settings;
    });

    const getSetting = (name) => {
        return settings.value[name];
    }

    return {
        getSetting,
        timeZone: settings.value.time_zone,
        timeFormat: settings.value.time_format,
        weekStartsOn: settings.value.week_starts_on
    }
}

export default useSettings;
