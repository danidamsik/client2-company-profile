<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Masuk Admin" />

        <div class="mb-7">
            <p class="text-body-sm font-bold uppercase text-brand-accent">Akses Admin</p>
            <h1 class="mt-2 text-display-sm text-brand-ink">Masuk ke dashboard</h1>
            <p class="mt-3 text-body-sm text-stone-600">
                Gunakan akun admin untuk mengelola layanan, sertifikasi, galeri, dan pesan calon klien.
            </p>
        </div>

        <div v-if="status" class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-body-sm font-medium text-green-800">
            {{ status }}
        </div>

        <form class="grid gap-5" @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-2 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="admin@email.com"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-2 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-body-sm text-stone-700">Ingat saya</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="focus-ring rounded-md text-body-sm font-semibold text-brand-accent transition hover:text-brand-accentDark"
                >
                    Lupa password?
                </Link>
            </div>

            <div>
                <PrimaryButton class="w-full" :disabled="form.processing">
                    {{ form.processing ? 'Memproses...' : 'Masuk' }}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
