<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import Textarea from "@/Components/Textarea.vue";

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  background: String,
})
const form = useForm({
    name: '',
    email: '',
    phone:'',
    address:'',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
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
  <Head title="Register"/>
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
              :href="route('dashboard.index')"
              :class="`rounded-md px-3 py-2 ${getActiveClass(route('dashboard.index'))}`"
              class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"
          >
            Home
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
          <h1 style="font-size: 40px">Join us now</h1>
          <p style="font-size: 22px">
          Create an account and register to complete the access process.
          Simply fill out this form and you're ready to start.
          We are excited to have you with us!
          </p>
          <p class="mt-5" style="font-size: 22px">
          If you have already signed up, please log in to access your account.</p>
        </div>
        <div class="col-md-6" style="width: 600px; margin: 10px; text-align: center; line-height: 75px; font-size: 30px">
          <div class="col-md-6 w-full max-w-md bg-white shadow-lg p-8 rounded-md">
            <h1 class="text-2xl font-bold mb-4 text-center">Register</h1>
            <form @submit.prevent="submit">
            <div>
              <InputLabel for="name" value="Name" class="text-left"/>

              <TextInput
                  id="name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.name"
                  required
                  autofocus
                  autocomplete="name"
              />

              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
              <InputLabel for="email" value="Email" class="text-left" />

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
              <InputLabel for="phone" value="Phone Number" class="text-left" />

              <TextInput
                  id="phone"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.phone"
                  required
                  autocomplete="phone"
              />

              <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="mt-4">
              <InputLabel for="address" value="address" class="text-left" />

              <TextInput
                  id="address"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.address"
                  required
                  autocomplete="address"
              />

              <InputError class="mt-2" :message="form.errors.address" />
            </div>

            <div class="mt-4">
              <InputLabel for="password" value="Password" class="text-left" />

              <TextInput
                  id="password"
                  type="password"
                  class="mt-1 block w-full"
                  v-model="form.password"
                  required
                  autocomplete="new-password"
              />

              <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
              <InputLabel for="password_confirmation" value="Confirm Password" class="text-left"/>

              <TextInput
                  id="password_confirmation"
                  type="password"
                  class="mt-1 block w-full"
                  v-model="form.password_confirmation"
                  required
                  autocomplete="new-password"
              />

              <InputError class="mt-2" :message="form.errors.password_confirmation"/>
            </div>

            <div class="mt-4 flex items-center justify-end">
              <Link
                  :href="route('login')"
                  class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                Already registered?
              </Link>

              <PrimaryButton
                  class="ms-4"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
              >
                Register
              </PrimaryButton>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
</template>
