<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  background: String,
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
      <Head title="Confirm Password" />
      <GuestLayout :can-login="canLogin" :can-register="canRegister" :background="background">
        <div class="flex justify-end items-center h-screen px-4" style="width: 800px">
          <div class="ml-30 col-md-6 w-full max-w-md bg-white shadow-lg p-8 rounded-md">
            <div class="mb-4 text-sm text-gray-600">
              This is a secure area of the application. Please confirm your
              password before continuing.
            </div>

            <form @submit.prevent="submit">
            <div>
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 flex justify-end">
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Confirm
                </PrimaryButton>
            </div>
        </form>
          </div>
        </div>
    </GuestLayout>
</template>
