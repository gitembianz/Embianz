<script>
    const lastRecord = document.getElementById('last_record');
    const options = {
        root: null,
        threshold: 1,
        rootMargin: '0px'
    }

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                @this.loadMore()
                observer.unobserve(lastRecord); // Stop observing to avoid multiple calls
            }
        });
    }, options);

    observer.observe(lastRecord);
</script>
