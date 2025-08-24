<template>
  <div class="files-container">

    <div v-if="loading" class="status-message">Loading files...</div>
    <div v-if="error" class="status-message error">{{ error }}</div>

    <ul v-if="!loading && files.length" class="files-list">
      <li v-for="file in files" :key="file.id" class="file-item">
        <div class="file-info">
          <a :href="`/files/${file.id}/download`" target="_blank" rel="noopener" class="file-name">
            {{ file.original_name }}
          </a>

          <div class="file-meta">
            <span class="tag">{{ formatSize(file.size) }}</span>
            <span class="tag">{{ getFileExtension(file.mime_type) }}</span>
            <span class="tag tag-date" :title="formatFullDate(file.created_at)">
              {{ formatDate(file.created_at) }}
            </span>
          </div>
        </div>

        <div class="file-actions">
          <a :href="`/files/${file.id}/download`" 
             :download="file.original_name" 
             target="_blank" 
             rel="noopener"
             class="btn btn-download"
             title="Download">
            Download
          </a>

          <button @click="deleteFile(file.id)" 
                  class="delete-btn" 
                  :disabled="deletingId === file.id"
                  :title="'Delete ' + file.original_name"
          >
            <span v-if="deletingId === file.id">Deleting...</span>
            <span v-else>Delete</span>
          </button>
        </div>
      </li>
    </ul>

    <div v-if="pagination.last_page > 1" class="pagination-wrapper">
      <nav class="pagination">
        <button @click="changePage(1)" :disabled="pagination.current_page === 1" class="pagination-btn">First</button>
        <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="pagination-btn">Previous</button>
        <span class="pagination-info">Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
        <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page" class="pagination-btn">Next</button>
        <button @click="changePage(pagination.last_page)" :disabled="pagination.current_page === pagination.last_page" class="pagination-btn">Last</button>
      </nav>
      <div class="pagination-summary">
        Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} files
      </div>
    </div>

    <div v-if="!loading && files.length === 0" class="status-message">No files found.</div>
  </div>
</template>

<script>
export default {
  props: {
    searchQuery: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      files: [],
      loading: false,
      error: null,
      deletingId: null,
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0,
        from: 0,
        to: 0
      },
      mimeTypeMap: {
        'application/pdf': 'PDF',
        'application/msword': 'DOC',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'DOCX',
        'application/vnd.ms-excel': 'XLS',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'XLSX',
        'application/vnd.oasis.opendocument.text': 'ODT',
        'image/jpeg': 'JPG',
        'image/png': 'PNG',
        'text/plain': 'TXT'
      }
    };
  },
  watch: {
    searchQuery() {
      // Reset to first page whenever search query changes
      this.fetchFiles(1);
    }
  },
  mounted() {
    this.fetchFiles();
  },
  methods: {
    async fetchFiles(page = 1) {
      this.loading = true;
      this.error = null;
      try {
        let path = `/files?page=${page}`;
        if (this.searchQuery) {
          path += `&search=${encodeURIComponent(this.searchQuery)}`;
        }
        const response = await fetch(path, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
          },
          credentials: 'same-origin'
        });
        if (!response.ok) {
          this.error = `Failed to load files: ${response.statusText}`;
          return;
        }
        const data = await response.json();
        this.files = data.data;
        this.pagination = {
          current_page: data.current_page,
          last_page: data.last_page,
          per_page: data.per_page,
          total: data.total,
          from: data.from,
          to: data.to
        };
      } catch {
        this.error = 'Error fetching files.';
      } finally {
        this.loading = false;
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.pagination.last_page) {
        this.fetchFiles(page);
      }
    },
    getFileExtension(mimeType) {
      return this.mimeTypeMap[mimeType] || mimeType.split('/').pop().toUpperCase();
    },
    formatSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
    },
    formatFullDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString();
    },
    async deleteFile(fileId) {
      if (!confirm('Are you sure you want to delete this file?')) return;

      this.deletingId = fileId;
      this.error = null;

      try {
        await axios.delete(`/files/${fileId}`);
        this.fetchFiles(this.pagination.current_page);
      } catch (error) {
        if (error.response && error.response.data && error.response.data.message) {
          this.error = error.response.data.message;
        } else {
          this.error = 'Network or server error while deleting file.';
        }
      } finally {
        this.deletingId = null;
      }
    }
  }
};
</script>


<style scoped>
.files-container {
  width: 100%;
  margin: 20px 0;
  padding: 20px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f9fafb;
  border-radius: 10px;
  border: 1px solid #ddd;
}

.status-message {
  text-align: center;
  padding: 15px;
  font-weight: 500;
  color: #555;
}

.error {
  color: #b91c1c;
  background-color: #fee2e2;
  border: 1px solid #f87171;
  margin-bottom: 15px;
  border-radius: 6px;
}

.files-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.file-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 15px;
  border-bottom: 1px solid #e5e7eb;
  background: white;
  border-radius: 6px;
  margin-bottom: 10px;
  transition: box-shadow 0.3s ease;
}

.file-item:hover {
  box-shadow: 0 4px 7px rgba(0, 0, 0, 0.05);
}

.file-info {
  display: flex;
  flex-direction: column;
}

.file-name {
  font-weight: 600;
  color: #2563eb;
  text-decoration: none;
  font-size: 1.05rem;
}

.file-name:hover {
  text-decoration: underline;
}

.file-meta {
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
  user-select: none;
  margin-top: 4px;
}

.tag {
  background-color: #e0e7ff;
  color: #3730a3;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  white-space: nowrap;
}

.tag-date {
  background-color: #dbeafe;
  color: #2563eb;
  cursor: default;
}

.file-actions {
  display: flex;
  gap: 10px;
  align-items: center;
  flex-shrink: 0;
}

.btn-download {
  background-color: #2563eb;
  color: white;
  border: none;
  padding: 7px 12px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  box-shadow: 0 2px 6px rgb(37 99 235 / 0.4);
  transition: background-color 0.3s ease;
  flex-shrink: 0;
  text-decoration: none;
}

.btn-download:hover {
  background-color: #1e40af;
}

.delete-btn {
  background-color: #ef4444;
  color: white;
  border: none;
  padding: 7px 12px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  box-shadow: 0 2px 6px rgb(239 68 68 / 0.4);
  transition: background-color 0.3s ease;
  flex-shrink: 0;
}

.delete-btn:disabled {
  background-color: #fca5a5;
  cursor: not-allowed;
  box-shadow: none;
}

.delete-btn:hover:not(:disabled) {
  background-color: #b91c1c;
}

.pagination-wrapper {
  margin-top: 20px;
  text-align: center;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
}

.pagination-btn {
  background-color: #f3f4f6;
  color: #374151;
  border: 1px solid #d1d5db;
  padding: 8px 12px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s ease;
}

.pagination-btn:hover:not(:disabled) {
  background-color: #2563eb;
  color: white;
  border-color: #2563eb;
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-info {
  margin: 0 10px;
  font-weight: 600;
  color: #374151;
}

.pagination-summary {
  font-size: 0.875rem;
  color: #6b7280;
}
</style>
