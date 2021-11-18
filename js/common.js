document.addEventListener('DOMContentLoaded', function(){

    async function blockAppears(idx, ms) {

        return new Promise((resolve, reject) => {
            setTimeout(() => {
                let elem = document.getElementById(`block_${idx}`);
                elem.style.opacity = "1";
                resolve(`resolvED! ${idx}`)
            }, ms)
        });

    }

    for (let i = 0; i < 11; i++) {
        blockAppears( i,i*100)
    }
})