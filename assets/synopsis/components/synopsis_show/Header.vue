<template>
    <header>
        <div class="row">
            <div class="col-10">
                <h1 class="display-5 mb-3">{{ title }}</h1>
            </div>
            <div class="col-2 text-end">
                <div class="btn-group">
                    <router-link :to="{ name: 'SynopsisIndex' }" class="btn btn-sm btn-secondary" v-tooltip="'Liste'">
                        <i class="fa-solid fa-arrow-left"></i>
                    </router-link>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
export default {
    name: 'Header',

    props: {
        title: String,
        id: Number
    },
}
</script>
