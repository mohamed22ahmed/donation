<script setup>
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Textarea from "@/Components/Textarea.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import '@fortawesome/fontawesome-free/css/all.css';

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
    onFinish: () => {
      form.name = '';
      form.email = '';
      form.message = '';
      this.showmessage = 'message sent successfully';
    },
  });
};

const { url } = usePage();

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
  <Head title="Contact Us"/>
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

    <div class="justify-end  h-screen items-center" style="display: flex; flex-direction: row; justify-content: space-around;">
      <div class="col-md-6" style="width: 600px; margin: 10px; text-align: center;">
        <h1 style="font-size: 40px">keep in Touch</h1>
        <p style="font-size: 22px">
          We'd love to hear from you!
          Whether you have a question, feedback, feel free to reach out.
          We are here to assist you and will get back to you as soon as possible.
        </p>
        <p class="mt-5" style="font-size: 22px">
          Please fill out this form , and we'll respond to your as soon as possible.
        </p>
      </div>

      <div class="col-md-6" style="width: 600px; margin: 10px; text-align: center; line-height: 75px; font-size: 30px">
        <div class="col-md-6 w-full max-w-md bg-white shadow-lg p-8 rounded-md">
          <h1 class="text-2xl font-bold mb-3 text-center">Contact Us</h1>
          <div class="row">
            <span class="inline-flex rounded-md justify-content-center">
              <a href="https://www.facebook.com/shahd.alaa.58555941?rdid=OMQtY0Et5BCCWQIs" target="_blank" style="margin-left:10px;color: #2563eb">
                <i class="fab fa-facebook"></i>
              </a>
              <a href="mailto:shahdalaa878@gmail.com" target="_blank" style="margin-left:10px;color: #2563eb">
                <i class="fa-solid fa-envelope"></i>
              </a>
              <a href="https://web.whatsapp.com/" target="_blank" style="margin-left:10px;color: springgreen">
                <i class="fab fa-whatsapp"></i>
              </a>
            </span>
          </div>

          <form @submit.prevent="submit">
            <div class="mt-4">
              <InputLabel for="name" value="Name" class="text-left" />
              <TextInput
                  id="name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.name"
                  placeholder="Enter Your Name"
                  required
                  autocomplete="name"
              />
              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Email Field -->
            <div class="mt-4">
              <InputLabel for="email" value="Email" class="text-left" />
              <TextInput
                  id="email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.email"
                  placeholder="Enter Your Email"
                  required
                  autocomplete="username"
              />
              <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Message Field -->
            <div class="mt-4">
              <InputLabel for="message" value="Message" class="text-left" />
              <Textarea
                  v-model="form.message"
                  id="message"
                  class="mt-1 block w-full"
                  required
                  autocomplete="on"
                  aria-placeholder="Write Your Message"
                  style="height: 150px"
                  placeholder="Write Your Message"
              />
              <InputError class="mt-2" :message="form.errors.message" />
            </div>

            <div class="mt-4 flex items-center justify-center">
              <PrimaryButton
                  class="ms-4"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
              >
                Submit
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>