<template>
    <div class="container about p-3">
        <!-- <h3 class="pb-2"><b>{{ $t("about.title") }}</b></h3>
        <p>{{ $t("about.p1") }}</p>
        <p>{{ $t("about.p2") }}</p>
        <p>{{ $t("about.p3") }}</p>
        <p>{{ $t("about.p4") }}</p>
        <p>{{ $t("about.p5") }}</p> -->
        <div class="col-12">
            <h3 class="question">Τι είναι το www.greekparliament.info;</h3>
            <p class="answer">Το www.greekparliament.info είναι μια απόποιρα εξόρυξης δεδομένων από τα πρακτικά της ελληνικής Βουλής και παρουσίασης τους με εμφανίσιμο τροπο. Προς το παρόν, η σελίδα είναι σε δοκιμαστική έκδοση.</p>
        </div>
        <div class="col-12">
            <h3 class="question">Τι είδους δεδομένα υπάρχου;</h3>
            <ul class="answer-list">
                <li>Όλες οι συνεδριάσεις που βρίσκονται στην επίσημη σελίδα της Βουλής (www.hellenicparliament.gr) με τις ομιλίες χωρισμένες ανα βουλευτή. Αυτή τη στιγμή διαθέτουμε όλες τις ομιλίες απο το 1989 μέχρι σήμερα.</li>
                <li>Δεδομένα σχετικά με τον κάθε βουλευτή (κοινοβουλευτική πορεία).</li>
                <li>Δεδομένα σχετικά με το κάθε κόμμα η κοινοβουλευτική ομάδα ενεργή την περίοδο 1989 μέχρι σήμερα.</li>
            </ul>
        </div>
        <div class="col-12">
            <h3 class="question">Κάποια κομμάτια της σελίδας δεν δουλεύουν σωστά/Κάποιες ομιλίες ή συνεδριάσεις δεν είναι διαθέσιμες/Οι αναζητήσεις είναι πάρα πολύ αργές;</h3>
            <p class="answer">Η σελίδα είναι ακόμα υπο κατασκευή και δυστυχώς τα προβλήματα δε μπορούν να λυθούν πολύ γρήγορα. Το κύριο πρόβλημα είναι οτι ο server μας είναι πολύ μικρός για τον όγκο των δεδομένων που παράγουμε και επεξεργαζόμαστε. Με τη μεταφορά μας στο μέλλον σε μεγαλύτερο server και πιο ανεπτυγμένες τεχνολογίες θα έχουμε τη δυνατότητα να παρέχουμε καλύτερες υπηρεσίες . Παρ' όλ' αυτά, κάθε feedback σχετικά με τις δυσκολίες που έχετε στη χρήση της σελίδας είναι ευπρόσδεκτη.</p>
        </div>
        <div class="col-12">
            <h3 class="question">Πώς αναπτύχθηκε το project;</h3>
            <p class="answer">Για την εξόρυξη των δεδομένων (scraping και parsing) χρησιμοποιούμε python και τη βιβλιοθήκη NLTK. Η σελίδα είναι φτιαγμένη σε PHP/Laravel/Vue.js. Βαση δεδομένων είναι η MySQL, με πλάνο  στο μέλλον να γίνει migrate σε MongoDB και ElasticSearch.</p>
        </div>
        <div class="col-12">
            <h3 class="question">Ποιά είναι τα μελλοντικά στοιχέια που θέλετε να προσθέσετε;</h3>
            <ul class="answer-list">
                <li>Λίνκ σε βίντεο απο κάθε συνεδρίαση.</li>
                <li>Παραπάνω στοιχεία για κάθε ομιλιτη (Πρόεδρος συνεδρίασης, Υπουργός κλπ).</li>
                <li>Παραπάνω στοιχεία για κάθε ομιλία (Επερώτηση, Κατάθεση αναφορών).</li>
                <li>Συγκεντρωμένα στατιστικά ομιλιών για κάθε συνεδρίαση η κοινοβουλευτική περίοδο ανα βουλευτή/κόμμα.</li>
                <li>Πρακτικά απο άλλες συνεδριάσεις (πχ δημοτικά συμβούλια, άλλες κοινοβουλευτικές επιτροπές).</li> 
            </ul>
        </div>
        <div class="col-12">
            <h3 class="question">Θελω να ασχοληθω κι εγώ με το project. Πως μπορώ;</h3>
            <p class="answer">Το πρόγραμμα είναι ακόμα σε εμβρυακό στάδιο και αναπτύσσεται απο 3 άτομα (οι οποίοι έχουμε αναλάβει και όλα τα έξοδα) , οπότε κάθε βοήθεια είναι χρήσιμη. Χρειάζεται βοήθεια στα εξής:</p>
            <ul class="answer-list">
                <li>Αναφορά bugs.</li>
                <li>Testing των αρχείων και έυρεση λαθών.</li>
                <li>Έρευνα για παραπάνω στοιχεία βουλευτών και χειροκίνητη προσθήκη των δεδομένων. </li>
            </ul>
            <p class="answer-num-2">Αν είστε developer η Data Scientist, κάθε βοήθεια/ιδέα είναι ευπρόσδεκτη. Το ίδιο και έχετε κάποιον γρήγορο και αξιόποστο διαθέσιμο server να προσφέρετε. Παρακαλω επικοινωνήστε μαζί μας.</p>
            <p class="answer-num-3">Στην πραγματικότητα όμως η σημαντικότερη βοήθεια που μπορείτε να προσφέρετε είναι η διάδοση της σελίδας μας. Δεν διαφημιζόμαστε οπότε βασιζόμαστε στο word of mouth και την καλή φήμη.</p>
        </div>
        <div class="col-12">
            <h3 class="question">Μπορω να εχω τα δεδομένα σε raw form για την έρευνα ή την επιχείρηση μου;</h3>
            <p class="answer">Πιθανότατα ναι. Επικοινωνήστε μαζί μας σε Direct message.</p>
        </div>
    </div>


    <!-- <p>This project is about monitoring what the parliament members are discussing in the greek parliament</p>
    <p>Greek people have the right to know what goes on parliament and what politicians are doing in there name.
        From this page greek citicens can view what every parliament member is saying on their behalf.
        
    </p> -->
    <!-- <p>
        The source of the data is the speech transcripts that are uploaded after every conference to the official
        website of parliament.
        
        We use a text parser to extract the data from txt, doc and docx files.
        
        We match each speech speaker with a name of the popolo speaker database in order to have data about each speakers
        membership through out the years of hes duty in parliament.
        
        Errors exist in the data, the process of the extraction and matching of speaker names with the popolo database
        cannot be error prone, so if you discover any mismaches of errors please let as know.

        The page is under development all the time.
        We are a very small team of developers so new features are taking a long time to be implemented, be pasient.

        For any questions, suggestions and error reports feel free to contact us.
    </p> -->

</template>

<script>
export default {

}
</script>