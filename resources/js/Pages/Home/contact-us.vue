<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Textarea from "@/Components/Textarea.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  background: String,
});
const form = useForm({
  message: '',
  email: '',
  name: '',
});
const submit = () => {
  form.post(route('contact-us'), {
    onFinish: () => form.reset(),
  });
};
</script>

<template>
  <Head title="Contact Us" />
  <GuestLayout :can-login="canLogin" :can-register="canRegister" :background="background">
    <div class="flex justify-center items-center h-screen px-4" style="width: 400px">
      <div class="w-full max-w-md bg-white shadow-lg p-8 rounded-md">
        <form @submit.prevent="submit">
          <div class="mt-4">
            <InputLabel for="Name" value="name" />

            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
                autocomplete="name"
            />

            <InputError class="mt-2" :message="form.errors.name" />
          </div>

          <div class="mt-4">
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full"
                v-model="form.email"
                required
                autocomplete="username"
            />

            <InputError class="mt-2" :message="form.errors.email" />
          </div>

          <div class="mt-4">
            <InputLabel for="message" value="Message"/>

            <Textarea
                v-model="form.message"
                style="height: 200px; width: 300px"
                id="message"
                required
                autocomplete="on"
                placeholder="Write Your Message">
            </Textarea>

            <InputError class="mt-2" :message="form.errors.message" />
          </div>

          <div class="mt-4 flex items-center justify-center">
            <SecondaryButton
                class="ms-4" style="background-color: #2563eb; color: white"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                type="submit"
            >
              Submit
            </SecondaryButton>
          </div>
        </form>
      </div>
    </div>
  </GuestLayout>
</template>
