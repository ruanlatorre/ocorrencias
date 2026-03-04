<!-- Topbar / Header Component -->
<header class="topbar">
    <div class="user-info">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent-red)" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" width="20" height="20">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
        </svg>
        <span class="greeting">Olá, <span class="name">
                <?php echo htmlspecialchars($username); ?>
            </span></span>
    </div>

    <div class="date-info">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent-red)" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" width="18" height="18" style="margin-right: 8px;">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        <?php echo $date; ?>
    </div>
</header>