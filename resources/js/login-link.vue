<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    label: { type: String, default: () => 'Login' },
    class: { type: String, default: () => 'underline' },
    email: String,
    key: String,
    redirectUrl: String,
    guard: String,
    userAttributes: { type: Object, default: () => null },
})

function submit() {
    router.post(route('loginLinkLogin'), {
        email: props.email,
        key: props.key,
        redirect_url: props.redirectUrl,
        guard: props.guard,
        user_attributes: props.userAttributes,
    })
}
</script>

<template>
    <form @submit.prevent="submit">
        <button :class="props.class" type="submit">
            {{ props.label }}
        </button>
    </form>
</template>
