<template>
    <div id="chapter-reader">
        <div class="container">
            <div class="pagination">
                <ul class="pagination pagination-lg pagination_top">
                    <li class="page-item"><a href="#" v-if="hasPrev" @click.prevent="prevPage()">Назад</a></li>
                    <li class="page-item"><a href="#" v-if="hasPrevLong" @click.prevent="getPage(currentPage - listPage)"><<</a></li>
                    <li class="page-item"><a href="#" v-if="hasFirst" @click.prevent="getPage(1)">1</a></li>
                    <li class="page-item"><span v-if="hasFirst">...</span></li>
                    <li class="page-item"><a href="#" v-for="button in buttons" @click.prevent="getPage(button)" :class="{current: currentPage == button}">{{ button }}</a></li>
                    <li class="page-item"><span v-if="hasLast">...</span></li>
                    <li class="page-item"><a href="#" v-if="hasNextLong" @click.prevent="getPage(currentPage + listPage)">>></a></li>
                    <li class="page-item"><a href="#" v-if="hasNext" @click.prevent="nextPage()">Вперёд</a></li>
                </ul>
            </div>
            <div ref="page" class="page" v-html="text"></div>
            <div class="pagination">
                <ul class="pagination pagination-lg pagination_bot">
                    <li class="page-item"><a href="#" v-if="hasPrev" @click.prevent="prevPage()">Назад</a></li>
                    <li class="page-item"><a href="#" v-if="hasPrevLong" @click.prevent="getPage(currentPage - listPage)"><<</a></li>
                    <li class="page-item"><a href="#" v-if="hasFirst" @click.prevent="getPage(1)">1</a></li>
                    <li class="page-item"><span v-if="hasFirst">...</span></li>
                    <li class="page-item"><a href="#" v-for="button in buttons" @click.prevent="getPage(button)" :class="{current: currentPage == button}">{{ button }}</a></li>
                    <li class="page-item"><span v-if="hasLast">...</span></li>
                    <li class="page-item"><a href="#" v-if="hasNextLong" @click.prevent="getPage(currentPage + listPage)">>></a></li>
                    <li class="page-item"><a href="#" v-if="hasNext" @click.prevent="nextPage()">Вперёд</a></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'chapter-reader',
    props: {
        id: {
            type: Number,
            required: true
        },
        range: {
            type: Number,
            required: false,
            default: 1
        }
    },
    data () {
        return {
            pages: [],
            currentPage: 1,
            listPage: 5
        }
    },
    computed: {
        // количество страниц
        count () {
            return this.pages.length
        },
        // текст страницы
        text () {
            return this.getText()
        },
        rangeStart () {
            // let start = this.currentPage - this.range;
            let start = this.currentPage;
            return (start > 0) ? start : 1
        },
        rangeEnd () {
            // let end = this.currentPage + this.range;
            let end = this.currentPage;
            return (end < this.count) ? end : this.count
        },
        hasFirst () {
            return this.rangeStart > 1
        },
        hasLast () {
            return this.rangeEnd < this.count
        },
        hasPrev () {
            return this.currentPage !== 1
        },
        hasNext () {
            return this.currentPage < this.count
        },
        hasNextLong () {
            return this.currentPage + this.listPage < this.count
        },
        hasPrevLong () {
            return this.currentPage - this.listPage > 1
        },
        // массив кнопок в пагинации
        buttons () {
            let buttons = [];
            for (let i = this.rangeStart; i <= this.rangeEnd; i ++) {
                buttons.push(i)
            }
            return buttons
        }
    },
    methods: {
        // получение страниц с сервера
        getChapter (id) {
            let url = `/api/getChapter/${id}`;
            this.$http.get(url).then(res => {
                let data = res.body;
                this.pages = data.data.text
            })
        },
        // текст страницы
        getText () {
            return this.pages[this.currentPage - 1]
        },
        // номер страницы
        getPage (page) {
            this.currentPage = page
        },
        // следующая страница
        nextPage () {
            return this.currentPage ++
        },
        // предыдущая страница
        prevPage () {
            return this.currentPage --
        },
        // занрузка скролла
        runScroll () {
            let el = document.querySelector('.page');
            return this.$scrollTo(el, 1000)
        }
    },
    created () {
        this.getChapter(this.id)
    },
    watch: {
        // наблюдатель за сменой страниц
        currentPage () {
            this.runScroll();
            return this.getText()
        }
    }
}
</script>

<style>
    * {
        margin: 0;
        padding: 0;
    }
    #chapter-reader .page{
        position: static!important;
    }
    .pagination {
        text-align: center;
        margin: 10px auto;
    }
    .pagination .page-item a{
        margin: 0 5px;
    }
    .pagination a {
      display: inline-block;
      padding: 7px;
      border: 1px solid silver;
      text-decoration: none;
    }
    .pagination a.current {
      border: 1px solid red;
    }
    .example h2{
        margin-top: 50px;
    }
    /***MEDIA***/
    @media (max-width: 640px) {
        .pagination .page-item a{
            margin: 0 1px;
        }
    }
</style>

