<?php

    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=about_djordje_minic.doc");

    $text_word = "<table>
        <thead>
            <tr>
                <th>Đorđe Minić 135/19</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Student sam Visoke ICT škole u Beogradu. U Novoj Varoši sam završio muzičku školu, ali kada sam zakoračio u svet programiranja shvatio sam da sam na pravom putu. Pored znatiželje i volje da unapredim i poboljšam moje znanje iz oblasi IT, takođe, sve više sam zainteresovan za nove izazove i rad u oblasti grafičkog dizajna. Kontaktirajte me, otvoren za saradnju i nove projekte.</td>
            </tr>
            <tr>
                <td> Za više informacija kao i uvid u moje dosadašnje projekte i znanje posetite moj Linkedin nalog ili portfolio!</td>
            </tr>
            <tr>
                <td><a href=\"https://petupolaslukom.github.io/Portfolio/\"> Portfolio </a>.</td>
            </tr>
            <tr>
                <td>Mreže:</td>
            </tr>
            <tr>
                <td><a href=\"https://github.com/PetUpolaSlukom\"> GitHub </a></td>
            </tr>
            <tr>
            <td>
                <a href=\"https://www.linkedin.com/in/djordje-minic-088343198/\"> LinkedIn </a>.</td>
            </tr>
            </tbody>
    </table>";

    echo $text_word;
   