//  <template>
//     <div>
//         <i class="notification-icon" @click='showNotifications'>🔔</i>
//         <div v-if="notifications.length" class="notifications-dropdown">
//             <div v-for="notification in notifications" :key="notification.id">
//                 {{ notification.data.status }}
//             </div>
//         </div>
//     </div>
// </template>

// <script>
// export default {
//     data() {
//         return {
//             notifications: []
//         };
//     },
//     mounted() {
//         window.Echo.private('App.Models.User.' + this.userId)
//             .notification((notification) => {
//                 this.notifications.push(notification);
//             });
//     },
//     computed: {
//         userId() {
//             return document.querySelector('meta[name="user-id"]').getAttribute('content');
//         }
//     }
// };
// </script>

// <style>
// .notification-icon {
//     cursor: pointer;
// }
// .notifications-dropdown {
//     position: absolute;
//     background: white;
//     border: 1px solid #ccc;
//     width: 300px;
//     max-height: 400px;
//     overflow-y: auto;
// }
// </style> 
