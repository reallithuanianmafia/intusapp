<template>
    <div class="container mt-5">
        <short-url-form></short-url-form>
    </div>
</template>

<script>
import ShortUrlForm from './components/ShortUrlForm.vue';

export default {
  components: {
    'short-url-form' : ShortUrlForm
  },
}
</script>