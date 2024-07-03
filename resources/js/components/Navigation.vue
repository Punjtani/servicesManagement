<template>
    <div class="col-sm-6 col-4 text-end  d-flex justify-content-end w-100">
        <p @click="$router.go(-1)" class="h3 mb-2 cursor-pointer history_back_btn" v-bind:class="{ 'disable-backward': !canGoBack }"  ><b-icon icon="arrow-left-circle-fill"></b-icon></p>
        <p @click="$router.go(1)" class="h3 mb-2 cursor-pointer history_farward_btn"  v-bind:class="{ 'disable-forward': !canGoForward }"><b-icon icon="arrow-right-circle-fill"></b-icon></p>
    </div>
</template>

<script>
export default {

    data() {
        return {
            imagePath:"",
            index:"",
        }
    },
    computed: {
        canGoForward() {
           let canGoForw = false;
try {
    canGoForw =  window.navigation.canGoForward;
} catch (error) {
      if (window.history && typeof window.history.goForward === 'function') {         canGoForw = window.history.goForward();
    } else if (window.history && (window.history.length > 1 && window.history.length > (history.state && history.state.index || 0))) {
        canGoForw = true;
    } else {
        canGoForw = false;
    }
}
return canGoForw;
        },
        canGoBack() {
            let canGo = false;
try {
    canGo =  window.navigation.canGoBack;
} catch (error) {
    if (window.history && typeof window.history.goBack === 'function') {
        canGo =  true;
    } else if (window.history && window.history.length > 1) {
        canGo = true;
    } else {
        canGo = false;
    }
}
return canGo;
        }
    },

}
</script>
