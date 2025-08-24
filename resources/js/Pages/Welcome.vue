<script setup>
import SecureCloudFile from '@/assets/SecureCloudFile.png'
import Cloudupload from '@/assets/Cloudupload.jpg'
import { Head, Link } from '@inertiajs/vue3';


defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="SimpleDrop: File Manager" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img
            id="background"
            class="absolute -left-0 top-0  h-full"
            src="https://laravel.com/assets/img/welcome/background.svg"
        />
        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header
                    class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3"
                >
                    <div class="flex lg:col-start-2 lg:justify-center">
                        <img :src="SecureCloudFile" class="h-20 w-auto text-white lg:h-20 lg:text-[#FF2D20]"></img>
                        
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-6">
                   <div class="max-w-4xl mx-auto p-6">
  <a
    href="/register"
    class="flex flex-col lg:flex-row items-center gap-6 bg-white dark:bg-zinc-900 rounded-xl p-8 shadow-lg ring-1 ring-white/5 dark:ring-zinc-800 transition hover:ring-black/20 dark:hover:ring-zinc-700 focus:outline-none focus-visible:ring-[#FF2D20]"
  >
    <!-- Image container -->
    <div class="w-full lg:w-1/3 h-48 lg:h-56 bg-[#FF2D20]/10 rounded-lg overflow-hidden flex-shrink-0">
      <img
        :src="Cloudupload"
        alt="Cloud upload illustration"
        class="w-full h-full object-cover"
      />
    </div>

    <!-- Text content -->
    <div class="w-full lg:w-2/3 text-center lg:text-left">
      <h2 class="text-3xl font-semibold text-black dark:text-white">
        SimpleDrop
      </h2>
      <p class="mt-4 text-base leading-relaxed text-gray-700 dark:text-gray-300">
        SimpleDrop provides a seamless, secure solution for storing and sharing your most valuable
        documents and photos.<br/>
        <br/> With robust access controls, you can confidently drop all your
        important files into SimpleDrop’s cloud environment, knowing they’re protected against
        unauthorized access and data loss.
      </p>
    </div>

    <!-- Arrow icon -->
    <div class="mt-6 lg:mt-0 flex-shrink-0">
      <svg
        class="w-8 h-8 stroke-[#FF2D20]"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
        />
      </svg>
    </div>
  </a>
</div>

                </main>

                <footer
                    class="py-16 text-center text-sm text-black dark:text-white/70"
                >
                    SimpleDrop V1.0.0
                </footer>
            </div>
        </div>
    </div>
</template>
