<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="synopsisMetaModal" tabindex="-1" aria-labelledby="synopsisMetaModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="synopsisMetaModalConfigLabel">Méta données</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-light"><i class="fa-solid fa-hashtag fa-fw"></i> id</th>
                                    <td>
                                        {{ synopsis.id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light"><i class="fa-solid fa-link fa-fw"></i> Slug</th>
                                    <td>
                                        {{ synopsis.slug }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light"><i class="fa-solid fa-calendar-plus fa-fw"></i> Création</th>
                                    <td>
                                        {{ formatDatetime(synopsis.createdAt) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light"><i class="fa-solid fa-pen-to-square fa-fw"></i> Dernière mise à jour</th>
                                    <td>
                                        {{ synopsis.updatedAt ? formatDatetime(synopsis.updatedAt) : '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import dayjs from 'dayjs';
import 'dayjs/locale/fr';
dayjs.locale('fr');

export default {
    name: 'MetaDataModal',

    props: {
        synopsis: Object,
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },

        formatDatetime(datetime) {
            return dayjs(datetime).format('DD MMMM YYYY HH:mm')
        }
    },
}
</script>

<style>
#synopsisMetaModal {
    display: block;
    z-index: 3000;
}

.modal-backdrop {
    z-index: 2080;
}
</style>