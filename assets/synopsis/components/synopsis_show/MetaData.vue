<template>
    <section>
        <h2 class="h5 mt-4">Méta-données</h2>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th class="bg-light"><i class="fa-solid fa-hashtag fa-fw"></i> id</th>
                    <td>
                        {{ element.id }}
                    </td>
                </tr>
                <tr>
                    <th class="bg-light"><i class="fa-solid fa-link fa-fw"></i> Slug</th>
                    <td>
                        {{ element.slug }}
                    </td>
                </tr>
                <tr>
                    <th class="bg-light"><i class="fa-solid fa-calendar-plus fa-fw"></i> Création</th>
                    <td>
                        {{ formatDatetime(element.createdAt) }}
                    </td>
                </tr>
                <tr>
                    <th class="bg-light"><i class="fa-solid fa-pen-to-square fa-fw"></i> Dernière mise à jour</th>
                    <td>
                        {{ element.updatedAt ? formatDatetime(element.updatedAt) : '' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
</template>

<script>
import dayjs from 'dayjs';
import 'dayjs/locale/fr';
dayjs.locale('fr');

export default {
    name: 'MetaData',

    props: {
        element: Object,
    },

    methods: {
        formatDatetime(datetime) {
            return dayjs(datetime).format('DD MMMM YYYY HH:mm')
        }
    },
}
</script>

<style scoped>
@media (min-width: 768px) {
    th.bg-light {
        width: 30%;
    }
}

@media (min-width: 992px) {
    th.bg-light {
        width: 22%;
    }
}
</style>
