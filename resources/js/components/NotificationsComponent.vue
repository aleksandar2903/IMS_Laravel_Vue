<template>
    <li class="nav-item dropdown">
        <a
            class="nav-link"
            href="#"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            <i class="ni ni-bell-55"></i>
            <span
                v-if="notifications.length"
                class="badge badge-danger sec position-absolute top-0"
                >{{ notifications.length }}</span
            >
        </a>
        <div
            class="
        dropdown-menu dropdown-menu-xl dropdown-menu-right
        py-0
        overflow-hidden
      "
        >
            <div class="px-3 py-3">
                <h6 class="text-sm text-muted m-0">
                    {{ text.Have }}
                    <strong class="text-primary"
                        >({{
                            notifications.length ? notifications.length : 0
                        }})</strong
                    >
                    {{ text.notifications }}.
                </h6>
            </div>
            <div
                class="list-group list-group-flush"
                v-for="notification in notifications"
                :key="notification.id"
            >
                <a href="#!" class="list-group-item list-group-item-action"
                    ><div class="row align-items-center">
                        <div class="col-auto">
                            <img
                                alt="Image placeholder"
                                :src="
                                    '/storage/images/' +
                                        notification.data.product.image.w500
                                "
                                class="avatar rounded-circle"
                            />
                        </div>
                        <div class="col ml--2">
                            <div
                                class="d-flex justify-content-between align-items-center"
                            >
                                <div>
                                    <h4 class="mb-0 text-sm">
                                        {{ notification.data.product.name }}
                                    </h4>
                                </div>
                                <div class="text-right text-muted">
                                    <small></small>
                                </div>
                            </div>
                            <p class="text-sm mb-0">
                                {{ text.Stock }}:
                                <span class="text-danger">
                                    {{ notification.data.product.stock }}
                                </span>
                            </p>
                        </div>
                    </div></a
                >
            </div>
        </div>
    </li>
</template>
<script>
export default {
    props: ["text"],
    created() {
        this.getNotifications();
        setInterval(() => {
            this.getNotifications();
        }, 60000);
    },
    data() {
        return {
            notifications: 0
        };
    },
    methods: {
        getNotifications() {
            axios
                .get("/unreadNotifications")
                .then(response => (this.notifications = response.data));
            console.log(this.notifications);
        }
    }
};
</script>
