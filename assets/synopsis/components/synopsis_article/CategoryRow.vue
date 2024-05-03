<template>
    <div class="mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-bold"> <i :class="'fa-solid ' + category.icon + ' fa-fw'"></i> {{ category.title }}</span>
                <div class="small text-muted" style="white-space: pre-wrap;">{{ category.description }}</div>
            </div>
            <div class="rounded bg-light p-1" role="button"  @click="showArticles = !showArticles">
                <i class="fa-solid fa-chevron-right fa-fw" v-if="showArticles === false"></i> 
                <i class="fa-solid fa-chevron-down fa-fw" v-if="showArticles === true"></i>
            </div>
        </div>
        <ul class="list-unstyled mb-0 mt-2 ms-2 border-start ps-2" v-if="showArticles">
            <li v-for="article in articles" :key="article.id">
                <a href="#" role="button" @click.prevent="setSelected(article)">{{ article.title }}</a>
            </li>
        </ul>
    </div>
</template>

<script lang="js">
export default {
    name: 'CategoryRow',

    props: {
        category: Object,
        articles: Array
    },

    data() {
        return {
            showArticles: true
        }
    },

    methods: {
        setSelected(article) {
            this.$emit('on-select', article);
        }
    },
}
</script>