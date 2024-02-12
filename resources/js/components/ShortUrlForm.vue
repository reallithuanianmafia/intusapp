<template>
    <div>
        <div class="container mt-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">IntusApp URL Shortener</h5>
                    <form id="shorten-form" @submit.prevent="submitForm">
                        <div class="input-group mb-3">
                            <input type="url" class="form-control" placeholder="Enter your URL" v-model="originalUrl" name="original_url" required>
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit">Shorten</button>
                            </div>
                        </div>
                    </form>
                    <div v-if="shortUrl">
                        <p class="card-text"><a :href="shortUrl" target="_blank" rel="noopener noreferrer">{{ shortUrl }}</a></p>
                        <p><small>For example, my web server address: 127.0.0.1:8000/{{ shortUrl }}</small></p>
                        <a href="#" class="btn btn-primary" @click="copyToClipboard">Copy the generated URL</a>
                    </div>
                    <div v-if="error">
                        <p>Error: {{ error }}</p>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    Intus URL Shortener is a user-friendly tool for efficiently shortening long URLs, providing concise and easily shareable links.
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
export default {
    data() {
        return {
            originalUrl: '',
            shortUrl: '',
            error: '',
        };
    },
    methods: {
        submitForm() {
            this.error = '';

            // Function to submit Form
            axios.post('/create-short-url', { original_url: this.originalUrl })
                .then(response => {
                    // Function to check URL using Google Safe Browsing API
                    this.checkSafeBrowsing(response.data.short_url,this.originalUrl);
                })
                .catch(error => {
                    this.error = error.response.data.message || 'An error occurred.';
                });
        },
        checkSafeBrowsing(shortUrl, originalUrl) {
            axios.post('/check-safe-browsing', { url: originalUrl })
                .then(response => {
                    if (response.data.safe) {
                        this.shortUrl = shortUrl;
                    } else {
                        this.error = 'The URL is not safe.';
                    }
                })
                .catch(error => {
                    this.error = 'Error checking URL safety.';
                });
        },
        copyToClipboard() {
            // Temporary Input Element
            const input = document.createElement('input');
            input.value = this.shortUrl;
            document.body.appendChild(input);

            // Select the content
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices

            // Copy the content to the clipboard
            document.execCommand('copy');

            // Remove the temporary input element
            document.body.removeChild(input);

            // Feedback
            alert('Short URL copied to clipboard!');
        },
    },
};
</script>
