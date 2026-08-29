<script>
  import { onMount } from 'svelte';
  import { UserModel } from '../Models/UserModel';
  import { UserController } from '../Controllers/UserController';

  let users = [];
  let loading = false;
  let error = null;
  let userController = new UserController();

  onMount(() => {
    loadUsers();
  });

  async function loadUsers() {
    loading = true;
    error = null;
    try {
      await userController.fetchUsers();
      users = userController.getUsers();
    } catch (err) {
      error = 'Failed to load users';
      console.error(err);
    } finally {
      loading = false;
    }
  }

  async function handleDelete(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
      try {
        await userController.deleteUser(userId);
        await loadUsers();
      } catch (err) {
        error = 'Failed to delete user';
        console.error(err);
      }
    }
  }
</script>

<div class="user-list">
  <h2>Users</h2>
  
  {#if error}
    <div class="error">{error}</div>
  {/if}

  {#if loading}
    <div class="loading">Loading...</div>
  {:else}
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        {#each users as user}
          <tr>
            <td>{user.id}</td>
            <td>{user.name}</td>
            <td>{user.email}</td>
            <td>{user.role}</td>
            <td>
              <button on:click={() => handleDelete(user.id)}>Delete</button>
            </td>
          </tr>
        {/each}
      </tbody>
    </table>
  {/if}
</div>

<style>
  .user-list {
    padding: 20px;
  }

  .error {
    color: red;
    padding: 10px;
    background: #fee;
    border: 1px solid #fcc;
    margin-bottom: 10px;
  }

  .loading {
    padding: 20px;
    color: #666;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  th {
    background: #f5f5f5;
    font-weight: bold;
  }

  button {
    padding: 6px 12px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  button:hover {
    background: #c82333;
  }
</style>
