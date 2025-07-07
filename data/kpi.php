<?php
// KPI Functions
function getDateRangeForPeriod($period) {
    $today = new DateTime();
    
    switch ($period) {
        case 'current_week':
            $start = clone $today;
            $start->modify('monday this week');
            $end = clone $start;
            $end->modify('+6 days');
            break;
        case 'last_week':
            $start = new DateTime('monday last week');
            $end = clone $start;
            $end->modify('+6 days');
            break;
        case 'current_month':
            $start = new DateTime('first day of this month');
            $end = new DateTime('last day of this month');
            break;
        case 'last_month':
            $start = new DateTime('first day of last month');
            $end = new DateTime('last day of last month');
            break;
        case 'current_quarter':
            $quarter = ceil($today->format('n') / 3);
            $start = new DateTime($today->format('Y') . '-' . (($quarter - 1) * 3 + 1) . '-01');
            $end = clone $start;
            $end->modify('+2 months')->modify('last day of this month');
            break;
        case 'current_year':
            $start = new DateTime($today->format('Y') . '-01-01');
            $end = new DateTime($today->format('Y') . '-12-31');
            break;
        default:
            $start = clone $today;
            $start->modify('monday this week');
            $end = clone $start;
            $end->modify('+6 days');
    }
    
    return [
        'start' => $start->format('Y-m-d'),
        'end' => $end->format('Y-m-d')
    ];
}

function calculateKPIs($db, $period, $start_date = null, $end_date = null) {
    if ($start_date && $end_date) {
        $dateRange = ['start' => $start_date, 'end' => $end_date];
    } else {
        $dateRange = getDateRangeForPeriod($period);
    }
    
    $kpis = [];
    
    try {
        // Total Candidates
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM _candidates WHERE Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['total_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // New Candidates
        $stmt = $db->prepare("SELECT COUNT(*) as new_candidates FROM _candidates WHERE Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['new_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['new_candidates'];
        
        // Active Candidates
        $stmt = $db->prepare("SELECT COUNT(*) as active FROM _candidates WHERE Status = 'active' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['active_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['active'];
        
        // Inactive Candidates
        $stmt = $db->prepare("SELECT COUNT(*) as inactive FROM _candidates WHERE Status = 'inactive' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['inactive_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['inactive'];
        
        // Archived Candidates
        $stmt = $db->prepare("SELECT COUNT(*) as archived FROM _candidates WHERE Status = 'archived' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['archived_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['archived'];
        
        // Pending Compliance
        $stmt = $db->prepare("SELECT COUNT(*) as pending FROM _candidates WHERE Status = 'pending' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['pending_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['pending'];
        
        // Top Job Titles
        $stmt = $db->prepare("SELECT JobTitle, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? AND JobTitle IS NOT NULL GROUP BY JobTitle ORDER BY count DESC LIMIT 5");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['top_job_titles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Top Cities
        $stmt = $db->prepare("SELECT City, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? AND City IS NOT NULL GROUP BY City ORDER BY count DESC LIMIT 5");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['top_cities'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Created By Stats
        $stmt = $db->prepare("SELECT CreatedBy, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? GROUP BY CreatedBy ORDER BY count DESC");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['created_by_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Daily Registration Trend
        $stmt = $db->prepare("SELECT DATE(Date) as date, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? GROUP BY DATE(Date) ORDER BY date");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['daily_trend'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate growth rate
        $previousPeriod = getPreviousPeriodRange($period, $dateRange);
        $stmt = $db->prepare("SELECT COUNT(*) as previous_total FROM _candidates WHERE Date BETWEEN ? AND ?");
        $stmt->execute([$previousPeriod['start'] . ' 00:00:00', $previousPeriod['end'] . ' 23:59:59']);
        $previous_total = $stmt->fetch(PDO::FETCH_ASSOC)['previous_total'];
        
        if ($previous_total > 0) {
            $kpis['growth_rate'] = round((($kpis['new_candidates'] - $previous_total) / $previous_total) * 100, 2);
        } else {
            $kpis['growth_rate'] = 0;
        }
        
        $kpis['date_range'] = $dateRange;
        
    } catch (Exception $e) {
        $kpis['error'] = $e->getMessage();
    }
    
    return $kpis;
}

function getPreviousPeriodRange($period, $currentRange) {
    $start = new DateTime($currentRange['start']);
    $end = new DateTime($currentRange['end']);
    $diff = $start->diff($end)->days + 1;
    
    $prevStart = clone $start;
    $prevStart->modify("-{$diff} days");
    $prevEnd = clone $end;
    $prevEnd->modify("-{$diff} days");
    
    return [
        'start' => $prevStart->format('Y-m-d'),
        'end' => $prevEnd->format('Y-m-d')
    ];
}
?>