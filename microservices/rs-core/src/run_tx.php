<script>
    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    async function demo() {
        for (let i = 0; i < 2000; i++) {
            window.open("http://localhost:8000/src/ether_tx_handler.php", "_blank");
            console.log('RUN '+i+' STARTED');
            await sleep(20000);
        }
        console.log('Done');
    }

    demo();
</script>