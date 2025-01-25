<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    background: String,
    canResetPassword: Boolean,
});

const form = useForm({
    email: '',
    verification: '',
});

const submit = () => {
    form.post(route('verification.store'), {
        onFinish: () => form.reset(),
    });
};

const { url } = usePage();

// Normalize URLs to match paths for comparison
const normalizeUrl = (fullUrl) => {
  const parser = new URL(fullUrl, window.location.origin);
  return parser.pathname;
};

const getActiveClass = (routeUrl) => {
  const currentPath = normalizeUrl(url);
  const targetPath = normalizeUrl(routeUrl);
  return currentPath === targetPath ? 'bg-gray-700 text-white' : 'text-gray-700';
};
</script>

<template>
  <Head title="Verification"/>
  <div class="relative min-h-screen" :style="`background-image: url('${background}'); background-size: cover; background-position: center;`">
    <nav class="absolute top-0 left-0 w-full bg-gray-100 shadow-md z-10">
      <div class="max-w-7xl mx-auto flex items-center h-16">
        <!-- Left Links -->
        <div class="flex space-x-4 flex-grow">
          <Link
              :href="route('home')"
              :class="`rounded-md px-3 py-2 ${getActiveClass(route('home'))}`"
              class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"
          >
            Home
          </Link>
          <Link
              :href="route('about')"
              :class="`rounded-md px-3 py-2 ${getActiveClass(route('about'))}`"
              class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"
          >
            About Us
          </Link>
          <Link
              :href="route('contact')"
              :class="`rounded-md px-3 py-2 ${getActiveClass(route('contact'))}`"
              class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"
          >
            Contact Us
          </Link>
        </div>

        <!-- Right Links -->
        <div class="flex justify-end space-x-4">
          <Link
              v-if="$page.props.auth.user"
              :href="route('dashboard')"
              :class="`rounded-md px-3 py-2 ${getActiveClass(route('dashboard'))}`"
              class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"
          >
            Dashboard
          </Link>
          <template v-else>
            <Link
                v-if="canLogin"
                :href="route('login')"
                :class="`rounded-md px-3 py-2 ${getActiveClass(route('login'))}`"
                class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white">
              Log in
            </Link>
            <Link
                v-if="canRegister"
                :href="route('register')"
                :class="`rounded-md px-3 py-2 ${getActiveClass(route('register'))}`"
                class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white">
              Register
            </Link>
          </template>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="justify-end  h-screen items-center" style="display: flex; flex-direction: row; justify-content: space-around;">
      <div class="col-md-6" style="width: 600px; margin: 10px; text-align: center;">
        <h1 style="font-size: 40px">Verify your account! </h1>
        <p style="font-size: 22px">
          Thanks for signing up! Before getting started, could you verify your
          email address by clicking on the link we just emailed to you? If you
          didn't receive the email, we will gladly send you another.
        </p>
      </div>
      <div class="col-md-6" style="width: 600px; margin: 10px; text-align: center; line-height: 75px; font-size: 30px">
        <div class="col-md-6 w-full max-w-md bg-white shadow-lg p-8 rounded-md">
          <h1 class="text-2xl font-bold mb-4 text-center">Verify your account</h1>
          <form @submit.prevent="submit">
            <div>
              <InputLabel for="email" value="Email" class="text-left" />

              <TextInput
                  id="email"
                  type="email"
                  name="email"
                  class="mt-1 block w-full"
                  v-model="form.email"
                  required
                  autofocus
              />
            </div>

            <div class="mt-4">
              <InputLabel for="verification" value="verification" class="text-left" />

              <TextInput
                  id="verification"
                  type="text"
                  name="verification"
                  class="mt-1 block w-full"
                  v-model="form.verification"
                  required
                  autocomplete="verification"
              />
            </div>

            <div class="mt-4 flex items-center justify-end">
              <Link
                  :href="route('verification.resend')"
                  class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                Resend Verification code
              </Link>

              <PrimaryButton
                  class="ms-4"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
              >
                Verify
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
